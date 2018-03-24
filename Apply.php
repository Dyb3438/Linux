<?php
session_start();
$username=$_POST['UserName'];
$userid=$_POST['UserId'];
$userpassword=$_POST['UserPassword'];
$code=$_POST['Code'];
$sex=$_POST['Sex'];
$birth=$_POST['Birth'];
$QQ=$_POST['QQ'];
$email=$_POST['Email'];
if($sex=="性别未知"){
    $sex="null";
}else{
    $sex="'".$sex."'";
}
if($birth==""){
    $birth="null";
}else{
    $birth="'".$birth."'";
}
if($QQ==""){
    $QQ="null";
}else{
    $QQ="'".$QQ."'";
}
if($email==""){
    $email="null";
}else{
    $email="'".$email."'";
}
if($_FILES['UserPhoto']['size']>0) {
    $str = "/(.)+\/((.)+)/";
    preg_match($str, $_FILES["UserPhoto"]["type"], $output);
    $diction = "userphoto/" . $userid . "." . $output[2];
    move_uploaded_file($_FILES["UserPhoto"]["tmp_name"], $diction);
}else{
    $diction="userphoto/root.png";
}
if($code!=$_SESSION['code']){
    $msg="验证码错误！";
    $result="N";
}else{
    $msg="";
    include_once("pdo_db.php");
    $sql = "insert into `users` values(null,'$username','$userid','$userpassword','$diction',$sex,$birth,$QQ,$email,'0')";
    $res = $dbh->exec($sql);
    if($res){
        $result="Y";
        $msg="";
        $_SESSION['userid']=$userid;
        $_SESSION['timeout']=time()+24*60*60;
    }else{
        $result="N";
        $msg="未录入数据库";
    }
    include_once("pdo_usersdb.php");
    $sql_add="CREATE TABLE `users-db`.`$userid` (
    `id` INT NOT NULL auto_increment, 
    `userid` BIGINT(18) NOT NULL ,
    `m` int(1),
    `y` int(1),
    PRIMARY KEY (`id`),
    index(`m`),
    index(`y`)
    )";
    $res2=$dbh->exec($sql_add);
    include_once("pdo_usersmoment.php");
    $sql_add2="CREATE TABLE `$userid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `response` VARCHAR(255)  COLLATE utf8_bin,
  `comment` VARCHAR(255)  COLLATE utf8_bin,
  `friend_moment` VARCHAR(255)  COLLATE utf8_bin,
  `time` datetime,
  PRIMARY KEY (`id`)
)";
    $res_add2=$dbh->exec($sql_add2);
}
echo json_encode(array('result'=>$result,'msg'=>$msg));






