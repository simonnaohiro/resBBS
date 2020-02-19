<?php
if(!empty($_POST)){
  //バデーションチェック
  validRequired($res,'res');
  if(empty($err_msg)){
    try{
      //res番号を更新
      $latestResNum ++;

      $dbh = dbConnect();
      $sql = 'INSERT INTO test_bbs (res_num, thread_id, name, email, comment_val, comment_date) VALUES (:res_num, :thread_id, :name, :email, :comment_val, :comment_date)';
      $data = array(':res_num' => $latestResNum, ':thread_id' => $tID,
                    ':name' => $name, ':email' =>$email,
                    ':comment_val' => $res, ':comment_date' => date('Y-m-d H:i:s')
      );
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('投稿したページに遷移します');
        header('Location:?tID='.$tID);
      }
    }catch(Exception $e){
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG02;
    }
  }
}
