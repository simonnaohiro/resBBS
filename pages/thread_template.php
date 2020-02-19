<?php
  require('./functions/function.php');

  debug('============================');
  debug('スレッドページ');
  debug('============================');
  debugLogStart();
  //変数
  $name = ($_POST['name'] === "") ? "名無しさん" : $_POST['name'];
  $email = (isset($_POST['email'])) ? $_POST['email'] :  "" ;
  $res = (isset($_POST['res'])) ? $_POST['res'] : "" ;
  $tID = $_GET['tID'];
  $urlParam = `http://`.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  $getThreadComment = getResponse($tID);
  $thread_title = $getThreadComment[0]['thread_title'];
  $site_title = $thread_title;
  $latestResNum = count($getThreadComment);
  //ログイン認証
  require('./functions/auth.php');
  require('./functions/post_comment_function.php');

  debug('画面表示処理終了 >>>>>>>>>>>>>>>>>>>>>>>>>>');
  require('./template/head.php');
?>
  <header>
    <a href="board.php">掲示板へ</a>
  </header>
  <body class="thread-body">
    <div class="thread">
      <header>
        <h1><?php echo $thread_title ?></h1>
        <p></p>
      </header>
      <div class="thread-table">
        <?php
        foreach($getThreadComment as $key => $val){
        ?>
        <div class="comment-wrapper" >
          <!-- print response number,name,datetime and comment ID  -->
          <?php if($val['delete_flg']){?>
            <div class="name-wrapper">
              <p><?php echo $val['res_num']; ?> 名前：あべし！！ <?php echo $val['comment_date'] ?> ID:???</p>
              <p style="<?php if($val['email'] === "") echo 'display:none;' ;?>">email:<?php echo $val['email'] ;?></p>
            </div>
            <div class="comment-box">
              削除されました
            </div>
          </div>
          <?php }else{?>
          <div class="name-wrapper">
            <p><?php echo $val['res_num']; ?> 名前：<?php echo $val['name']; ?> <?php echo $val['comment_date'] ?> ID:<?php echo $val['commenter_id'] ?></p>
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
      </div>
      <section>
        <form class="post-wrapper" action="" method="post">
          <p><?php if(!empty($err_msg['chat'])) echo $err_msg['chat'] ; ?></p>
          <label class="name-post-wrapper">
            <input type="text" name="name" placeholder="名前">
            <input type="text" name="email" placeholder="Eメール">
          </label>
          <label>
            <textarea name="res" rows="8" cols="80"></textarea>
            <input type="submit" value="送信">
          </label>
        </form>
      </section>
    </div>
    <div class="">
      <form class="" action="" method="post">
        <input type="submit" name="delete" value="消去">
        <?php
          $delete = (isset($_POST['delete'])) ? true : false ;
          if($delete){
            deleteThread();
          }
         ?>
      </form>
    </div>
  </body>
</html>
