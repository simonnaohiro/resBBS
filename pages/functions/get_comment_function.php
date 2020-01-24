<?php
$name = ($_POST['name'] == "") ? '禁断のミノリス774': $_POST['name'];
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$chat = (isset($_POST['chat'])) ? $_POST['chat'] : '';

if(!empty($_POST)){
  global $err_msg;
  validRequired($chat,'chat');
  validMaxLen($name,'name');
  validMaxLen($email,'email');
  if(empty($err_msg)){
    //pass the validation check
    $tableName = $_GET['tID'];
    try{
      $dbh = dbConnect();
      $sql = 'INSERT INTO resba_board (name, email, comment_val, comment_time , comment_date, commenter_id) VALUES (:name, :email, :chat, :comment_time, :comment_date, :commenter_id)';
      $data = array(':name' => $name , ':email' => $email , ':chat' => $chat , ':comment_time' => date('i:s') , ':comment_date' => date('Y-m-d H:i:s') , ':commenter_id' => makeRandId());
      debug('SQL:'.$sql);
      debug('流し込みデータ'.print_r($data,true));
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        $thispage = $_SERVER['REQUEST_URI'];
        debug('投稿したページに遷移します');
        header('Location:'.$thispage);
      }

    }catch(exception $e){
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG02;
    }
  }
}
