<?php
session_start();
if(isset($_SESSION['userid'])&&$_SESSION['timeout']>time()) {
    include_once("pdo_db.php");
    $userid=$_SESSION['userid'];
    $sql = "select `readed` from `chat-image` where `receiver`='$userid' and `readed`='0'";
    $res=$dbh->query($sql);
    $number=0;
    while($row=$res->fetch()){
        $number++;
    }
    if($number>99){
        $number="99+";
    }
    echo $number;
}