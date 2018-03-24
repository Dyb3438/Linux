<?php
if(isset($_COOKIE['userid'])){
    $userid=$_COOKIE['userid'];
    include_once("pdo_usersdb.php");
    $sql="select*from `$userid` where `m`='1' and `y`='1'";
    $res=$dbh->query($sql);
    $friend=array();
    $i=0;
    while($row=$res->fetch()){
        $i++;
        $friend[$i]=$row['userid'];
    }
    include_once("pdo_db.php");
    for($a=1;$a<=$i;$a++){
        $friend_id=$friend[$a];
        $sql_user="select `username`,`userphoto` from `users` where `userid`='$friend_id'";
        $res_user=$dbh->query($sql_user);
        $sql_readed="select `readed` from `chat-image` where `sender`='$friend_id' and `receiver`='$userid' and `readed`='0'";
        $res_readed=$dbh->query($sql_readed);
        $number=0;
        while($row_readed=$res_readed->fetch()){
            $number++;
        }
        if($number>99){
            $number="99+";
        }
        while($row_user=$res_user->fetch()){
            $friend[$a]=array(
                "userid"=>$friend_id,
                "username"=>$row_user['username'],
                "userphoto"=>$row_user['userphoto'],
                "number"=>$number
            );
        }
    }
    echo json_encode(array("friend"=>$friend));
}else{
    echo json_encode(array("result"=>"N","msg"=>"未登录"));
}