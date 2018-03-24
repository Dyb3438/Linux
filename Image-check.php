<?php
function userTextDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}
$id=$_GET['Id'];
$receiver=$_GET['Receiver'];
if(isset($_COOKIE['userid'])){
    include_once("pdo_db.php");
    $sender=$_COOKIE['userid'];
    $sql="select `id` from `chat-image` where `sender`=$receiver and `receiver`=$sender or `sender`=$sender and `receiver`=$receiver order by `id` desc limit 1";
    $res=$dbh->query($sql);
    $id2="0";
    while($row=$res->fetch()){
        $id2=$row['id'];
    }
    if($id!=$id2){
    $sql2="select*from `chat-image` where (`sender`=$receiver and `receiver`=$sender or `sender`=$sender and `receiver`=$receiver) and `id`>$id order by `id` asc";
    $res2=$dbh->query($sql2);
    $new_image=array();
    $i=1;
    while($row2=$res2->fetch()){
        if($row2['receiver']==$sender){
            $row2_id=$row2['id'];
            $sql_update="update `chat-image` set `readed`='1' where `id`='$row2_id'";
            $res_update=$dbh->exec($sql_update);
        }
        $new_image[$i]=array(
           "sender"=>$row2['sender'],
            "image"=>userTextDecode($row2['image']),
            "time"=>$row2['time']
        );
        $i++;
    }
    for($a=1;$a<$i;$a++){
        $chat_id=$new_image[$a]['sender'];
        $sql_id="select `username`,`userphoto` from `users` where `userid`='$chat_id'";
        $res_id=$dbh->query($sql_id);
        while($row_id=$res_id->fetch()){
            $new_image[$a]['sender']=array(
                "userid"=>$chat_id,
                "username"=>$row_id['username'],
                "userphoto"=>$row_id['userphoto']
            );
        }

    }
    }else{
        $new_image="";
    }
    echo json_encode(array("new_image"=>$new_image,"id"=>$id2));
}else{
    echo "未登录";
}

