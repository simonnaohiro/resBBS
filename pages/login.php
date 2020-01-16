<?php
require('./functions/function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「ログインページ「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('./functions/auth.php');
$site_title = 'ログインページ';
// ==============================
// ログイン処理
// ==============================
if(!empty($_POST)){
  debug('ポスト送信があります');

  $email = isset($_POST['email']) && is_string($_POST['email']) ? $_POST['email'] : "";
  $pass = isset($_POST['pass']) && is_string($_POST['pass']) ? $_POST['pass'] : "";
  $pass_save = (!empty($_POST['pass_save'])) ? true : false ;

  //emailの形式チェック
  validEmail($email, 'email');
  //最大文字数チェック
  validMaxLen($email, 'email');

  //パスワード半角英数字チェック
  validHalf($pass, 'pass');
  //パスワード最大文字数チェック
  validMaxLen($pass, 'pass');
  //パスワード最小文字数チェック
  validMinLen($pass, 'pass');
  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  if(empty($err_msg)){
    debug('バリデーションチェック完了');

    //例外処理
    try{

      //DBに接続
      $dbh = dbConnect('resba_board');
      //SQL文作成(users1から削除フラグがFalseで入力されたメールアドレスと保存されたメールアドレスが)
      $sql = 'SELECT password, id FROM users1 WHERE email = :email AND delete_flg = 0';
      $data = array( ':email' => $email );
      //クエリ実行
      $stmt =  queryPost($dbh, $sql, $data);
      //クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      debug('クエリ結果の中身:'.print_r($result,true));

      if(!empty($result) && password_verify($pass, array_shift($result))){
        debug('パスワードがマッチしました');

        //ログイン有効期限（デフォルトで１時間）
        $sesLimit = 60*60;
        //最終ログインタイムを更新
        $_SESSION['login_date'] = time();

        //ログイン保持にチェックがある場合
        if($pass_save){
          debug('ログイン保持がチェックされています');
          //ログイン有効期限を３０日にセット
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        }else{
          debug('ログイン保持がチェックされていません');
          //次回からログイン保持しないので、ログイン有効期限を１時間後にセット
          $_SESSION['login_limit'] = $sesLimit;
        }
        //ユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];

        debug('セッション変数の中身:'.print_r($_SESSION,true));
        debug('マイページへ遷移します');
        header('Location:mypage.php');
      }else{
        debug('パスワードがアンマッチです');
        $err_msg['common'] = MSG09;
      }

    }catch(Exception $e){
      error_log('エラー発生:'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }

  }

}
debug('画面表示終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <?php require('./template/head.php'); ?>
  <body>
    <?php require('header.php'); ?>
    <div class="container">
      <div class="form-wrapper">
        <h1>ログインページ</h1>
        <form class="signup-form" method="post">
          <label>
            <div class="msg_area"><p><?php if(!empty($err_msg)) echo $err_msg['email']; ?></p></div>
            <input type="text" name="email" placeholder="メールアドレス">
          </label>
          <label>
            <div class="msg_area"><p><?php if(!empty($err_msg)) echo $err_msg['pass']; ?></p></div>
            <input type="text" name="pass" placeholder="パスワード">
          </label>
          <label><p>ログイン状態を保持する <input type="checkbox" name="pass_save" ></p></label>
          <label>
            <input type="submit" name="" value="送信">
          </label>
        </form>
        <div class="to_signup">
          <p>登録がお済みでない方は<a href="signup.php">こちら</a>から。 </p>
        </div>
      </div>
    </div>
  </body>
</html>
