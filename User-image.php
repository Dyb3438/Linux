<?php
if(isset($_COOKIE['userid'])) {
    $userid = $_COOKIE['userid'];
    include_once('pdo_db.php');
    $sql = "select `username`,`userphoto` from `users` where `userid`= $userid ";
    $res = $dbh->query($sql);
    $row = $res->fetch();
    $username = $row['username'];
    $userphoto = $row['userphoto'];
    $result="Y";
    $msg="";
}else{
    $result="N";
    $msg="未登录";
    $username ="";
    $userphoto ="";
    $userid="";
}
echo json_encode(array("result"=>$result,"msg"=>$msg,"username"=>$username,"userphoto"=>$userphoto,"userid"=>$userid));


