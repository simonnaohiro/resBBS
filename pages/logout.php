<?php
require('./functions/function.php');

debug('「「「「「「「「「「「「「「「「「');
debug('ログアウトページ');
debug('「「「「「「「「「「「「「「「「「');

debug('ログアウトします');

//セッション変数のクリア
$_SESSION = array();
//セッションを削除
session_destroy();
debug('ログインページへ遷移します');
header('Location:login.php');
