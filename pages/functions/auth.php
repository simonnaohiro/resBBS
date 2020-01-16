<?php
//===========================
//ログイン認証・自動ログアウト
//===========================
//ログインしている場合
if( !empty($_SESSION['login_date'])){
  debug('ログイン済みのユーザーです');

  if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
    debug('ログイン有効期限を超えています');

    //セッション破棄
    session_destroy();
    //ログインページへ遷移
    header('Location:login.php');
  }else{
    debug('ログイン有効期限内です');
    //最終ログイン日時を更新
    $_SESSION['login_date'] = time();

    //現在実行中のスクリプトファイルがlogin.phpの場合
    //$_SERVER['PHP_SELF']はドメインからのパスをかえす
    //さらにbasement関数を使うことでファイル名だけ取り出せる
    if( basename($_SERVER['PHP_SELF']) === 'login.php'){
      debug('マイページに遷移します');
      header('Location:mypage.php');
    }
  }
}else{
  debug('未ログインユーザーです');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
    header("Location:login.php");
  }
}
