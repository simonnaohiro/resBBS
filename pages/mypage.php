<?php
  require('./functions/function.php');
  require('./functions/auth.php');
  $site_title = 'マイページ';
 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <?php require('./template/head.php'); ?>
  <body>
    <?php require('header.php'); ?>
    <div class="container">
      <h1>マイページです</h1>
      <a href="logout.php">ログアウト</a>
    </div>
  </body>
</html>
