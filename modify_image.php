<?php
if(isset($_COOKIE['userid'])) {
    $userid=$_POST['UserId'];
    if ($userid == $_COOKIE['userid']) {
        $username=$_POST['UserName'];
        $sex=$_POST['Sex'];
        $birth=$_POST['Birth'];
        $QQ=$_POST['QQ'];
        $email=$_POST['Email'];
        $xuliehao=$_POST['Number'];
        if ($sex == "性别未知") {
            $sex = "null";
        } else {
            $sex = "'" . $sex . "'";
        }
        if ($birth == "") {
            $birth = "null";
        } else {
            $birth = "'" . $birth . "'";
        }
        if ($QQ == "") {
            $QQ = "null";
        } else {
            $QQ = "'" . $QQ . "'";
        }
        if ($email == "") {
            $email = "null";
        } else {
            $email = "'" . $email . "'";
        }
        include_once("pdo_db.php");
        if ($_FILES['UserPhoto']['size'] > 0) {
            $str = "/(.)+\/((.)+)/";
            preg_match($str, $_FILES["UserPhoto"]["type"], $output);
            $diction = "userphoto/" . $userid . "." . $output[2];
            move_uploaded_file($_FILES["UserPhoto"]["tmp_name"], $diction);
            $sql2 = "update `users` set `userphoto`='$diction' where `userid`=$userid";
            $res2 = $dbh->exec($sql2);
        }else{
            $res2=1;
        }
        if ($xuliehao == "fklajKLAJD544658ikO9" or $xuliehao == "iexikKWJIK54654ueO8" or $xuliehao == "fkaldKJFKIE87458kdJ7") {
            $sql = "update `users` set `username`='$username',`sex`=$sex,`birth`=$birth,`QQ`=$QQ,`email`=$email,`power`='2' where `userid`=$userid";
        } else if ($xuliehao == "fklajKLAJD544658ikO8") {
            $sql = "update `users` set `username`='$username',`sex`=$sex,`birth`=$birth,`QQ`=$QQ,`email`=$email,`power`='0' where `userid`=$userid";
        } else {
            $sql = "update `users` set `username`='$username',`sex`=$sex,`birth`=$birth,`QQ`=$QQ,`email`=$email where `userid`=$userid";
            $a=1;
        }
        $res = $dbh->exec($sql);
        if ($res or $a==1) {
            echo json_encode(array("result" => "Y"));
        } else if ($res2) {
            echo json_encode(array("result" => "Y"));
        } else {
            echo json_encode(array("result" => "N", "msg" => "修改错误"));
        }
    }else{
        echo json_encode(array("result"=>"N","msg"=>"与登录id不符"));
    }
}else{
    echo json_encode(array("result"=>"N","msg"=>"未登录"));
}