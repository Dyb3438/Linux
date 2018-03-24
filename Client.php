<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2018/1/23
 * Time: 9:23
 */
session_start();
$userid=$_POST['UserId'];
$userpassword=$_POST['UserPassword'];
$code=$_POST['Code'];
if($code==$_SESSION['code']) {
    include_once('pdo_db.php');
    $check = "select userid,userpassword from users";
    $res = $dbh->query($check);
    while ($row = $res->fetch()) {
        if ($userid == $row['userid']) {
            if ($userpassword == $row['userpassword']) {
                $msg = "";
                $result = "Y";
                setcookie("userid",$userid,time()+24*60*60);
                $a = 0;
            } else {
                $msg = "密码错误！";
                $result = "N";
                $a = 0;
            }
        }
    }
}else{
    $msg="验证码错误！";
    $result="N";
    $a=0;
}
if(isset($a)){
}else{
    $msg="用户账号不存在，请先注册";
    $result="N";
}
echo json_encode(array("result"=>$result,"msg"=>$msg));
