<?php
//====ログの設定====
//ログを取るかどうか
ini_set('log_errors', 'on');
//ログの出力ファイルを指定
ini_set('error_log', 'php.log');

//====デバッグ====
//debugフラグ
$debug_flg = true;
//デバグ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ:'.$str);
  }
}

//====sessionの設定=====
//sessionPathを設定（/var/tmp/に設定すると削除するまでの日数が３０日まで延長される）
session_save_path('/var/tmp/');
//gabage_collectionが削除するセッションの有効期限を設定（３０日以上立ってるものに対して100分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じた場合に削除されるセッションの有効期限を延長
ini_set('session.cookie_lifetime', 60*60*24*30);
//セッション開始
session_start();
//現在のセッションIDを新しいIDにおきかえる
session_regenerate_id();

//====画面表示時のログ吐き出し関数====
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>> 画面処理開始');
  debug('セッションID:'.session_id());
  debug('セッション変数の中身:'.print_r($_SESSION,true));
  debug('現在日時スタンプ:'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug('ログイン期日日時タイムスタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
  }
}


//====定数====


define('MSG01','入力欄がカラです');
define('MSG02','最大文字数を超過しています');
define('MSG03','必要文字数に達していません');
define('MSG04','Emailの形式ではありません');
define('MSG05','そのEmailはすでに登録されています');
define('MSG06','エラーが発生しました。しばらく経ってからやり直してください');
define('MSG07','半角英数字のみ使用できます');
define('MSG08','入力された内容が一致しません');
define('MSG09','書き込みがありません');

//====グローバル変数====
//====エラーメッセージ====
$err_msg = array();
//====テーブル名====
$tableName = (isset($_GET['tID'])) ? $_GET['tID'] : "";

//====ヴァリデーション関数=====

function validRequired($str, $key){
  if($str === ""){
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
function validMaxLen($str, $key, $max = 255){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
function validMinLen($str, $key, $min = 3){
  if(mb_strlen($str) < $min){
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
function validEmail($str, $key){
  if(!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/",$str)){
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
function validDubEmail($email){
  global $err_msg;
  //例外処理
  try{
    //DB接続
    $dbh = dbConnect();
    //SQL文作成
    $sql = 'SELECT count(*) FROM users1 WHERE email = :email AND delete_flg = 0';
    $data = array(':email' =>$email);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    //クエリ結果の値を取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty(array_shift($result))){
      $err_msg['email'] = MSG05;
    }
  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
    $err_msg['common'] = MSG06;
  }
}
function validHalf($str, $key){
  global $err_msg;
  if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
    $err_msg[$key] = MSG07;
  }
}
function validMatch($str1, $str2, $key){
  global $err_msg;
  if($str1 !== $str2){
    $err_msg[$key] = MSG08;
  }
}
//====DB接続関数====
function dbConnect(){
  $dsn = "mysql:dbname=RBBS_DB;host=localhost;charset=utf8";
  $user = 'root';
  $password = 'root';
  $option = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  //PODオブジェクト生成（DBへ接続）
  $dbh = new PDO($dsn, $user, $password, $option);
  return $dbh;
}

function queryPost($dbh, $sql, $data){
  //クエリ作成
  $stmt = $dbh->prepare($sql);
  //プレースホルダーに値をセットしてSQLを実行
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました');
    $err_msg['common'] = MSG02;
    return 0;
  }
  debug('クエリ成功');
  return $stmt;
}
//ユーザー取得
function getUser(){
  debug('ユーザーを取得します');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM users1 WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql,$data);

    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:'.$e->getMessage());
  }
}
//===================
//掲示板関数
//===================
function makeRandId($length = 9){
  static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
  $str = "";
  for ($i = 0; $i < $length; $i++) {
    $str .= $chars[mt_rand(0,61)];
  }
  return $str;
}

function makeThreadRandId($length = 14){
  static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
  $str = "";
  for ($i = 0; $i < $length; $i++) {
    $str .= $chars[mt_rand(0,61)];
  }
  return $str;
}
//スレッド名取得関数
function getThread(){
  debug('全データを取得');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT thread_id, thread_title FROM test_bbs_thread_title WHERE delete_flg = 0';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生' . $e->getMessege());
  }
}
//コメント投稿関数
// function postComment(){
//   $dbh = dbConnect();
//   $sql = 'INSERT INTO ';
// }
//コメント取得
function getResponse($tID){
  try{
    $dbh = dbConnect();
    $sql = "SELECT t1.thread_id ,t1.thread_title, t2.res_num, t2.name, t2.email, t2.comment_val, t2.comment_date
                  FROM test_bbs_thread_title AS t1 INNER JOIN test_bbs AS t2 ON t1.thread_id = t2.thread_id
                  WHERE t1.thread_id IN (:thread_id)";
    $data = array(':thread_id' => $tID);

    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG02;
  }
}
//ページネーション関数
function pagination($currentPageNum, $totalPageNum, $link='', $pageColNum=5){
  //現在のページが、総ページ数と同じで総ページ数が表示項目数以上なら、左にリンクを４つ出す
  if($currentPageNum == $totalPageNum && $totalPageNum > $pageColNum){
    $minPageNum = $currentPageNum - 4;
    $maxPageNum = $currentPageNum;
    //現在のページが、総ページ数の１ページ前なら、左に３つ右に１つリンクを出す
  }elseif($currentPageNum == ($totalPageNum - 1) && $totalPageNum >$pageColNum){
    $minPageNum = $currentPageNum - 3;
    $maxPageNum = $currentPageNum + 1;
    //現在のページが２の場合は左に１つ右に３つリンクを出す
  }elseif($currentPageNum == 2 && $totalPageNum > $pageColNum){
    $minPageNum = $currentPageNum - 1;
    $maxPageNum = $currentPageNum + 3;
    //現在のページが１の場合は左には何も出さず、右に４つリンクを出す
  }elseif($currentPageNum == 1 && $totalPageNum > $pageColNum){
    $minPageNum = $currentPageNum;
    $maxPageNum = 5;
    //総ページ数が表示項目数がより少ない場合は
  }elseif($totalPageNum < $pageColNum){
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
    //それ以外は左右に２個出す
  }else{
    $minPageNum = $currentPageNum - 2;
    $maxPageNum = $currentPageNum + 2;
  }

  echo '<div class="pagination">';
    echo '<ul class="pagination-list">';
    if($currentPageNum != 1){
      echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
    }
    for($i = $minPageNum; $i <= $maxPageNum; $i++){
      echo '<li class="list-item ';
      if($currentPageNum == $i ){ echo 'active'; }
      echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
    }
    if($currentPageNum != $maxPageNum && $maxPageNum > 1){
      echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
    }
    echo '</ul>';
  echo '</div>';
}
//１の位を取得する関数
function getOnesPlaceCount($num){
  $numLen = strlen($num) - 1;
  $onesPlace = substr($num,$numLen,1);
  if($onesPlace != 0){
    return $onesPlace;
  }else{
    return 10;
  }
}
// 秒単位の数値を時間：分：秒に変換
function getHMSTime($sec){
  $ss = $sec % 60;
	$mm = (int)($sec / 60) % 60;
	$hh = (int)($sec / (60 * 60));
	return array($hh, $mm, $ss);
}
//数字を再生時間に置換する関数
function getVideoCommentTime($res_num, $timeUnit){
  $commentTime = $res_num * $timeUnit;
  if($commentTime > 3600){
    list($hh,$mm,$ss) = getHMSTime($commentTime);
    return $hh.':'.$mm.':'.$ss;
  }else{
    list(,$mm,$ss) = getHMSTime($commentTime);
    return $mm.':'.$ss;
  }
}
