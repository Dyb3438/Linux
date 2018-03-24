<?php
$userid=$_GET['Userid'];
include_once("pdo_db.php");
$sql="select*from `users` where `userid`=$userid";
$res=$dbh->query($sql);
while($row=$res->fetch()){
    $username=$row['username'];
    $userphoto=$row['userphoto'];
    if($row['sex']==1){
        $sex="男";
    }else if($row['sex']=="0"){
        $sex="女";
    }else if($row['sex']==NULL){
        $sex="未填写";
    }
    if($row['birth']==null){
     $birth="未填写";
    }else{
        $birth=$row['birth'];
    }
    if($row['QQ']==null){
        $QQ="未填写";
    }else{
        $QQ=$row['QQ'];
    }
    if($row['email']==null){
        $email="未填写";
    }else{
        $email=$row['email'];
    }
    if($row['power']=="0"){
        $identity="群众";
    }else if($row['power']=="1"){   
        $identity="版主";
    }else if($row['power']=="2"){
        $identity="管理员";
    }
}
if(isset($username)) {
    if(isset($_COOKIE['userid'])) {
        if ($_COOKIE['userid'] != $userid) {
            $visitor = $_COOKIE['userid'];
            include_once("pdo_usersdb.php");
            $sql = "select*from`$visitor`";
            $res = $dbh->query($sql);
            while ($row = $res->fetch()) {
                if ($userid == $row['userid']) {
                    if ($row['m'] == 1 and $row['y'] == 1) {
                        $guanzhu = "3";
                    } else if ($row['m'] == 1) {
                        $guanzhu = "1";
                    } else if ($row['y'] == 1) {
                        $guanzhu = "2";
                    }
                }
            }
            if (isset($guanzhu)) {
            } else {
                $guanzhu = "0";
            }
        } else {
            $guanzhu = "";
        }
    }else{
        $guanzhu="";
    }
        $output = array("userid" => $userid, "username" => $username, "userphoto" => $userphoto, "sex" => $sex, "birth" => $birth, "QQ" => $QQ, "email" => $email, "identity" => $identity, "guanzhu" => $guanzhu);
        echo json_encode($output);

}else{
    echo json_encode(array("result"=>"N"));
}