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
        <?php  if($_SERVER['REQUEST_URI'] !== '/res_BBS/pages/mypage.php'){
          ?><li><a href="mypage.php">マイページ</a></li><?php
        } ?>
        
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
