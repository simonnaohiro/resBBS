<?php
  $thread_title = (isset($_POST['thread_title'])) ? $_POST['thread_title'] : '';
  $name = ($_POST['name'] === "") ? '名無しさん': $_POST['name'];
  $email = (isset($_POST['email'])) ? $_POST['email']: '';
  $res = (isset($_POST['res'])) ? $_POST['res'] : '';
  if(!empty($_POST)){
    //バリーデーションチェック
    validRequired($thread_title, 'thread_title');
    validRequired($res, 'res');
    validMaxLen($email, 'email');
    validMaxLen($name, 'name');

    if(empty($err_msg)){
      //例外処理
      try {
        $thread_id = makeRandId(11);

        $dbh = dbConnect();
        $sql1 = 'INSERT INTO test_bbs (res_num, thread_id, name, email, comment_val, comment_date) VALUES (:res_num, :thread_id, :name, :email, :comment_val,:comment_date)';
        $data1 = array(
        ':res_num' => 1, ':thread_id' => $thread_id,
        ':name' => $name, ':email' => $email, ':comment_val' => $res,
        ':comment_date' => date('Y-m-d H:i:s')
        );
        $stmt1 = queryPost($dbh, $sql1, $data1);
        debug('sql:'.$sql1);
        if($stmt1){
          debug('書き込みしました');
          try{
            $sql2 = 'INSERT INTO test_bbs_thread_title (thread_id, thread_title) VALUES (:thread_id, :thread_title)';
            $data2 = array(':thread_id' => $thread_id, ':thread_title' => $thread_title);
            $stmt2 = queryPost($dbh, $sql2, $data2);
            debug('sql:'.$sql2);
            if($stmt2){
              debug('スレッドの作成に成功しました');
              debug('投稿したページに遷移します');
              header('Location:thread_template.php?tID='.$thread_id);
            }
          }catch(Exception $e){
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
