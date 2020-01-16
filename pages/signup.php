<?php
//共通関数の読み込み
require('functions/function.php');

debug('「「「「「「「「「「「「「「「「');
debug('ユーザー登録ページ');
debug('「「「「「「「「「「「「「「「「');
debugLogStart();
$site_title = 'ユーザー登録';
// POST送信があった場合
if(!empty($_POST)){
  //バリデーションチェック
  $email = isset($_POST['email']) && is_string($_POST['email']) ? $_POST['email'] : "";
  $pass = isset($_POST['pass']) && is_string($_POST['pass']) ? $_POST['pass'] : "";
  $pass_re = isset($_POST['pass_re']) && is_string($_POST['pass_re']) ? $_POST['pass_re'] : "";
  //入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');
  if(empty($err_msg)){
    //emailの形式チェック
    validEmail($email,'email');
    validMaxLen($email,'email');
    validDubEmail($email);
    //パスワード半角チェック
    validHalf($pass,'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass,'pass');
    validMaxLen($pass_re,'pass_re');
    //パスワードの最小文字数チェック
    validMinLen($pass,'pass');
    validMinLen($pass,'pass_re');

    if(empty($err_msg)){
      validMatch($pass,$pass_re,'pass');

      if(empty($err_msg)){
        //例外処理
        try{
          //DB接続
          $dbh = dbConnect();
          //SQL文
          $sql = 'INSERT INTO users1 (email, password, user_id, login_time, create_date) VALUES (:email, :pass, :user_id, :login_time, :create_date)';
          $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':user_id' => makeRandId(),
                        ':login_time' => date('Y-m-d H:i:s'),
                        ':create_date' => date('Y-m-d H:i:s')
                        );
          //クエリ実行
          $stmt = queryPost($dbh, $sql, $data);

          //クエリ成功の場合
          if($stmt){
            $sesLimit = 60*60;
            //最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;
            //ユーザーIDを格納
            $_SESSION['user_id'] = $dbh->lastInsertId();

            debug('セッション変数の中:'.print_r($_SESSION,true));

            header('Location:mypage.php');
          }
        }catch(Exception $e){
          error_log();
          $err_msg = MSG07;
        }
      }
    }
  }
}
 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <?php require('./template/head.php'); ?>
  <body>
    <?php require('header.php'); ?>
    <div class="container">
      <div class="form-wrapper">
        <h1>登録</h1>
        <form class="signup-form" method="post">
          <label>
            <div class="msg_area"><p><?php if(!empty($err_msg)) echo $err_msg['email']; ?></p></div>
            <input type="text" name="email" placeholder="メールアドレス">
          </label>
          <label>
            <div class="msg_area"><p><?php if(!empty($err_msg)) echo $err_msg['pass']; ?></p></div>
            <input type="text" name="pass" placeholder="パスワード">
          </label>
          <label>
            <div class="msg_area"><p><?php if(!empty($err_msg)) echo $err_msg['pass_re']; ?></p></div>
            <input type="text" name="pass_re" placeholder="パスワード(再入力)">
          </label>
          <label>
            <input type="submit" name="" value="送信">
          </label>
        </form>
      </div>
    </div>
  </body>
</html>
