<?php
  require('./functions/function.php');
  debug('「「「「「「「「「「「「「「「「「「「「「');
  debug('ニコレイアウト');
  debug('「「「「「「「「「「「「「「「「「「「「「');
  debugLogStart();

  $res = (isset($_POST['res'])) ? $_POST['res'] : "" ;
  $tID = (isset($_GET['tID'])) ? $_GET['tID'] : "" ;
  $getThreadComment = getResponse($tID);
  $thread_title = $getThreadComment[0]['thread_title'];
  $latestResNum = count($getThreadComment);
  // //ログイン認証
  require('./functions/auth.php');
  require('./functions/post_comment_function.php');

  debug('画面表示処理終了 >>>>>>>>>>>>>>>>>>>>>>>>>>');
 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>nico LAYOUT</title>
    <link rel="stylesheet" href="../src/css/reset.css">
    <link rel="stylesheet" href="../src/css/nico.css">
    <link href="https://fonts.googleapis.com/css?family=Oswald&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/124587158e.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <header>
      <div class="container">
        <div class="header-item">
          <ul class="header-left">
            <li>Top</li>
            <li>動画</li>
            <li>静画</li>
            <li>チャンネル</li>
            <li>アプリ</li>
          </ul>
          <ul class="header-right">
            <li>マイページ</li>
            <li>おすすめ</li>
            <li>ランキング</li>
            <li>メニュー<i></i></li>
          </ul>
        </div>
      </div>
    </header>
    <main>
      <div class="container">
        <div class="descrip-wrapper">
          <div class="heading-wrapper">
            <h1 class="video-heading"><?php echo $thread_title ?></h1>
            <p class="description">男６人大旅行です。<br>
              オーディオコメンタリー付き</p>
              <?php

              ?>
          </div>
          <div class="info-wrapper">
            <div class="user-info">

            </div>
            <div class="video-info">
            </div>
          </div>
        </div>
        <div class="video-wrapper">
          <div class="primary">
            <div class="primary-inner">
              <div class="sponsor">
                ここに広告
              </div>
              <div class="video">
                <div id="commentLayer">
                </div>
                <iframe id="media" src="https://www.youtube.com/embed/pbPy9NT9W6Q" ></iframe>
              </div>
              <div class="controller-wrapper">
              </div>
              <form class="form-wrapper" method="post">
                <input type="text" class="command-box" name="command" placeholder="コマンド" colspan="2">
                <input type="text" autocomplete="off" class="comment-form" name="res" placeholder="コメント">
                <input class="submit-btn" type="submit" value="コメントする">
              </form>
            </div>
          </div>
          <div class="secondary">
            <div class="sns-wrapper">
              <div class="menu">
                <div class="menu-bar">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </div>
              <div class="sns">
                <i class="fab fa-twitter"></i>
                <i class="fab fa-facebook"></i>
                <i class="fab fa-line"></i>
              </div>
            </div>
            <div class="comment-wrapper">
              <div class="list-switch">
                <ul>
                  <li>コメントリスト</li>
                  <li>他スレリスト</li>
                </ul>
              </div>
              <div class="selector">
                <p>チャンネルセレクト</p>
              </div>
              <div class="comment-box">
                <div class="comment-header">
                  <ul>
                    <li id="js-comment">書き込み</li>
                    <li id="js-comment-time">動画時間</li>
                    <li id="js-comment-date">書き込み日時</li>
                    <li id="js-comment-num">書き込み番号</li>
                  </ul>
                </div>
                <div class="comment-body">
                   <div id="js-comm-target" class="comment-container">
                     <?php
                     foreach($getThreadComment as $key => $val){
                      ?>
                    <div id="comment<?php echo $val['id'] ?>" class="comment comment-contents">
                      <?php
                       echo $val['comment_val'];
                      ?>
                    </div>
                    <?php
                      }
                     ?>
                   </div>
                  <div id="js-commT-target" class="comment-container comment-time-wrapper">
                    <?php
                    foreach ($getThreadComment as $key => $val) {
                      ?>
                      <div class="comment-time comment-contents">
                      <?php
                      if(!empty($val['comment_time'])){
                        echo $val['comment_time'];
                      }else{
                        $resNum = $val['res_num'];

                        $commentTime = getVideoCommentTime($resNum,10);
                        echo $commentTime;
                      }
                       ?>
                      </div>
                      <?php
                      }
                     ?>
                  </div>
                  <div id="js-commD-target" class="comment-container">
                    <?php
                    foreach ($getThreadComment as $key => $val) {
                     ?>
                    <div class="comment-date comment-contents">
                      <?php
                      echo $val['comment_date'];
                       ?>
                    </div>
                    <?php
                    }
                     ?>
                  </div>
                  <div id="js-commN-target" class="comment-container">
                    <?php
                    foreach ($getThreadComment as $key => $val) {

                     ?>
                    <div class="comment-num comment-contents">
                      <?php
                      echo $val['res_num'];
                       ?>
                    </div>
                    <?php
                    }
                     ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <footer>

    </footer>
    <script type="text/javascript" src="../src/js/getWidth.js"></script>
    <script type="text/javascript" src="../src/syncWidth.min.js"></script>
  </body>
</html>
