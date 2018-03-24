<?php
if(isset($_COOKIE['userid'])) {
    $code = $_POST['Code'];
    $password = $_POST['NewPassword'];
    $userid=$_COOKIE['userid'];
    if(isset($_COOKIE[$code])){
        $code_userid=$_COOKIE[$code];
        if($code_userid==$userid){
            include_once("pdo_db.php");
            $sql="update `users` set `userpassword`='$password' where `userid`=$userid";
            $res=$dbh->exec($sql);
            if($res){
                echo json_encode(array("result"=>"Y"));
                setcookie($code,"");
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