<?php
if(isset($_COOKIE['userid'])){
    $userid=$_COOKIE['userid'];
    $password=$_POST['Password'];
    include_once("pdo_db.php");
    $sql="select `userpassword` from `users` where `userid`=$userid";
    $res=$dbh->query($sql);
    while($row=$res->fetch()){
        if($password==$row['userpassword']){
            $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
                'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
                't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
                'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
                'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
            // 在 $chars 中随机取 $length 个数组元素键名
            $length=rand(10,15);
            $keys = array_rand($chars, $length);
            $code="";
            for($i = 0; $i < $length; $i++) {
                // 将 $length 个数组元素连接成字符串
                $code=$code.$chars[$keys[$i]];
            }
            setcookie($code,$userid,time()+60*2);
            echo json_encode(array("result"=>"Y","code"=>$code));
            $a=1;
        }
    }
    if(isset($a)){
    }else{
        echo json_encode(array("result"=>"N","msg"=>"密码错误"));
    }
}else{
    echo json_encode(array("result"=>"N","msg"=>"未登录"));
}

