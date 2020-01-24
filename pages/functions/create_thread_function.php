<?php
  $thread_title = (isset($_POST['thread_title'])) ? $_POST['thread_title'] : '';
  $name = ($_POST['name'] === "") ? '禁断のミノリス774': $_POST['name'];
  $email = (isset($_POST['email'])) ? $_POST['email']: '';
  $chat = (isset($_POST['chat'])) ? $_POST['chat'] : '';

  if(!empty($_POST)){
    //バリーデーションチェック
    // validRequired($thread_title, 'thread_title');
    validRequired($chat, 'chat');
    validMaxLen($email, 'email');
    validMaxLen($name, 'name');

    if(empty($err_msg)){
      //例外処理
      $threadDBName = 'response'.makeThreadRandId();
      try {
        $dbh = dbConnect();
        $sql = 'CREATE TABLE `resba_board`.`'.$threadDBName.'`
        (
          `id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
          `thread_title` varchar(255) DEFAULT NULL,
          `name` varchar(255) NOT NULL,
          `email` varchar(255) NOT NULL,
          `comment_val` text NOT NULL,
          `comment_time` time NOT NULL,
          `delete_flg` tinyint(1) NOT NULL DEFAULT "0",
          `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `commenter_id` varchar(255) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        $data = array();
        debug('SQL:'.$sql);
        debug('流し込みデータ:'.print_r($data, true));
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
          debug('スレッドを作成しました');
          try{
            $sql2 = 'INSERT INTO '.$threadDBName.' (thread_title, name, email, comment_val, comment_time , comment_date, commenter_id) VALUES (:thread_title, :name, :email, :chat, :comment_time, :comment_date, :commenter_id)';
            $data2 = array(':thread_title' => $thread_title , ':name' => $name , ':email' => $email , ':chat' => $chat , ':comment_time' => date('i:s') , ':comment_date' => date('Y-m-d H:i:s') , ':commenter_id' => makeRandId());
            debug('SQL:'.$sql2);
            debug('流し込みデータ:'.print_r($data2, true));
            //クエリ実行
            $stmt2 = queryPost($dbh, $sql2, $data2);
            if($stmt2){
              debug('>>1に書き込みました');
              debug('作ったスレッドに遷移します');
              $url = "http://localhost:8888/resba_board/thread_template.php?tID=".$threadDBName;
              header('Location:'.$url);
            }
          }catch (Exception $e){
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = MSG02;
          }
        }
      } catch(Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG02;
      }
    }
  }
