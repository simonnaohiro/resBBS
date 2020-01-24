<?php
  require('./functions/function.php');

  debug("===========================");
  debug('掲示板〜スレッド一覧ページ〜');
  debug("===========================");
  debugLogStart();
  //スレッド作成関数
  require('./functions/create_thread_function.php');
  // スレッド取得関数
  // require('./functions/get_tableName_function.php');
  //書き込み取得関数
  require('./functions/get_comment_function.php');
//
// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------------
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは１ページ目
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
// パラメータに不正な値が入っているかチェック
// if(!is_int($currentPageNum)){
//   error_log('エラー発生:指定ページに不正な値が入りました');
//   header("Location:index.php"); //トップページへ
// }
// 表示件数
$listSpan = 10;
// 現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //1ページ目なら(1-1)*20 = 0 、 ２ページ目なら(2-1)*20 = 20


$site_title = '掲示板にようこそ';
//スレッドの全データ
$getAllThread = getThread();
//総スレッド数
$threadNum = count($getAllThread);
//ページネーションのリンク数
$totalPageNum = intval(ceil($threadNum/$listSpan));
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
 ?>
<!DOCTYPE html>
<html lang="ja">
<?php require('./template/head.php'); ?>
  <body>
    <header class="board-header">
      <input type="text" name="search-thread" value="タイトルで検索">
    </header>
      <div class="board-container">
        <main>
          <section>
            <div class="sponsors-area">
              広告、またはお知らせを表示する。
            </div>
          </section>
          <section>
            <div class="thread-list-area">
              <?php
              for ($i=0; $i < $threadNum; $i++) {
                $tableName = $getAllThread[$i]['Tables_in_resba_board'];
                $getThreadComment = getComment($tableName);
                $threadPath = 'http://localhost:8888/resba_board/thread_template.php?tID='.$tableName;
                ?>
                <a class="thread-heading" href="<?php echo $threadPath ?>"><?php echo $getThreadComment[0]['thread_title']; ?></a>
                <?php
                }
                 ?>
            </div>
          </section>
          <section>
            <div class="thread-content">
              <?php
              $onesPlace = getOnesPlaceCount($threadNum);
              $startThreadCount = ($currentPageNum - 1) * 10;
              if($currentPageNum == $totalPageNum){
                $rpThreadCount = $startThreadCount + $onesPlace;
              }else{
                $rpThreadCount = $startThreadCount + $listSpan;
              }
              // var_dump($threadNum);
              // var_dump($onesPlace);
              // var_dump($rpThreadCount);
              for ($i=$startThreadCount; $i < $rpThreadCount; $i++) {
                $tableName = $getAllThread[$i]['Tables_in_resba_board'];
                $getThreadComment = getComment($tableName);
                $threadTitle = $getThreadComment[0]['thread_title'];
                $url = "http://localhost:8888/resba_board/thread_template.php?tID=".$tableName;
                ?>
              <div class="thread-wrapper">
                  <h1><?php echo $threadTitle; ?></h1>
                  <?php
                  foreach($getThreadComment as $key => $val){
                  ?>
                  <div class="comment-wrapper" >
                    <!-- print response number,name,datetime and comment ID  -->
                    <?php if($val['delete_flg']){?>
                      <div class="name-wrapper">
                        <p><?php echo $val['id']; ?> 名前：あべし！！ <?php echo $val['comment_date'] ?> ID:???</p>
                        <p style="<?php if($val['email'] === "") echo 'display:none;' ;?>">email:<?php echo $val['email'] ;?></p>
                      </div>
                      <div class="comment-box">
                        削除されました
                      </div>
                    <?php }else{?>
                    <div class="name-wrapper">
                      <p><?php echo $val['id']; ?> 名前：<?php echo $val['name']; ?> <?php echo $val['comment_date'] ?> ID:<?php echo $val['commenter_id'] ?></p>
                      <p style="<?php if($val['email'] === "") echo 'display:none;' ;?>">email:<?php echo $val['email'] ;?></p>
                    </div>
                    <!-- print a content of response-->
                    <div class="comment-box">
                     <p><?php
                     $text = $val['comment_val'];

                     //if $text has URL
                     if(preg_match_all('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', $text, $result) !== 0){
                       foreach($result[0] as $value){ ?>
                         <?php
                          $replace = '<a href="'.$value.'">'.$value.'</a>';
                          $text =  str_replace($value, $replace ,$text);
                          ?>
                         <?php
                       }
                       echo nl2br($text);

                     }else{
                       echo nl2br($text);
                     }
                     ?>
                     </p>
                    </div>
                  </div>
                  <?php
                  }
                  }
                   ?>
                   <a href="<?php echo $url ?>">スレッドへ</a>
                 </div>
                   <?php
                }
                ?>
                </div>
                <?php
                 pagination($currentPageNum,$totalPageNum);
                 ?>
              </div>
            </div>
          </section>
        </main>
        <section>
          <form class="post-wrapper" action="" method="post">
            <p><?php
             ?></p>
            <label>
              <input class="thread_title" type="text" name="thread_title" placeholder="スレッドタイトル">
            </label>
            <label class="name-post-wrapper">
              <input type="text" name="name" placeholder="名前">
              <input type="text" name="email" placeholder="Eメール">
            </label>
            <label>
              <textarea name="chat" rows="8" cols="80"></textarea>
              <input type="submit" value="送信">
            </label>
          </form>
        </section>
      </div>
  </body>
</html>
