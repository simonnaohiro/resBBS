<header>
  <div class="header-wrapper">
    <div class="header-left">
      <i><a href="index.php">Res Battle</a></i>
    </div>
    <div class="header-right">
      <ul>
        <?php if(!empty($_SESSION['login_date'])) {
          debug('ログイン済みのユーザーです');
          ?>
        <li><a href="index.php">TOP</a></li>
        <li><a href="mypage.php">マイページ</a></li>
        <li><a href="logout.php">ログアウト</a></li>
        <?php
        }else{
          debug('未ログインユーザーです');
        ?>
        <li><a href="index.php">TOP</a></li>
        <li><a href="login.php">ログイン</a></li>
        <?php
        }?>
      </ul>
    </div>
  </div>
</header>
