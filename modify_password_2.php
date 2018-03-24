<?php
session_start();
if(isset($_SESSION['userid'])&&$_SESSION['timeout']>time()) {
    $code = $_POST['Code'];
    $password = $_POST['NewPassword'];
    $userid=$_SESSION['userid'];
    if(isset($_SESSION[$code])){
        $code_userid=$_SESSION["$code"];
        unset($_SESSION["$code"]);
        if($code_userid==$userid){
            include_once("pdo_db.php");
            $sql="update `users` set `userpassword`='$password' where `userid`=$userid";
            $res=$dbh->exec($sql);
            if($res){
                echo json_encode(array("result"=>"Y"));
            }else{
                echo json_encode(array("result"=>"N","msg"=>"修改密码错误"));
            }
        }else{
            echo json_encode(array("result"=>"N","msg"=>"id不一样"));
        }
    }else{
        echo json_encode(array("result"=>"N","msg"=>"已过期"));
    }
}else{
    echo json_encode(array("result"=>"N","msg"=>"未登录"));
}