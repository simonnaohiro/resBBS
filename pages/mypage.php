<?php
  require('./functions/function.php');
  require('./functions/auth.php');
  $site_title = 'マイページ';
  $u_id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : "";
  $user_info = getUser($u_id);
  $user_name = (isset($user_info['username'])) ? $user_info['username'] : 'ゲスト' ;
  $create_date = (isset($user_info['create_date'])) ? $user_info['create_date'] : '' ;
  $description = (isset($user_info['description'])) ? $user_info['description'] : '' ;
 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <?php require('./template/head.php'); ?>
  <body>
    <?php require('header.php'); ?>
    <?php var_dump($user_info) ?>
    <div class="container">
      <div class="mypage-wrapper">
        <div class="mypage-header">

        </div>
        <div class="user-wrapper">
          <div class="user-img">
            <img  src="" alt="">
          </div>
          <div class="info-wrapper">
            <div class="user-info">
              <h1 ><?php echo $user_name; ?></h1><p>ID:<?php echo $user_info['user_id']; ?></p>
              <div class="profile">
                <p>登録日(更新日時):<?php echo $user_info['create_date']; ?></p>
                <div class="introduce">
                  <h1>PROFILE:</h1>
                  <p><?php echo $description; ?>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
                </div>
              </div>
            </div>
            <div class="side-box">
              <ul>
                <li class="prof-edit"><a href="#">プロフィール編集</a></li>
                <li class="responses"><a href="#">書き込み履歴</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
