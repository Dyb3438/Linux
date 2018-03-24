<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2018/1/22
 * Time: 14:43
 */
include("pdo_db.php");


if(isset($_POST['UserName'])){
    $sql="select username from users";
    $checkname=$dbh->query($sql);
    while($row=$checkname->fetch()){
        if($row['username']==$_POST['UserName']){
        echo json_encode(array('result'=>'N','msg'=>'用户昵称已存在'));
        $a="1";
        }
    }
}
if(isset($_POST['UserId'])){
    $sql="select userid from users";
    $checkid=$dbh->query($sql);
    while($row=$checkid->fetch()){
        if($row['userid']==$_POST['UserId']){
            echo json_encode(array('result'=>'N','msg'=>'用户账号已存在'));
            $a="1";
        }
    }
}
if(isset($a)){
} else{
    echo json_encode(array('result'=>'Y','msg'=>''));
}
