<?php
if(isset($_COOKIE['userid'])) {
    $followed = $_POST['Followed'];
    $userid=$_COOKIE['userid'];
    $type=$_POST['Type'];
    if ($userid != $followed) {
        include_once("pdo_usersdb.php");
        $sql_find1 = "select*from `$userid`";
        $res_find1 = $dbh->query($sql_find1);
        while ($row_find1 = $res_find1->fetch()) {
            if ($row_find1['userid'] == $followed) {
                if ($row_find1['m'] == "0") {
                    if($type=="1") {
                        $sql_add1 = "update `$userid` set `m`='1' where `userid`=$followed";
                        $res_add1 = $dbh->exec($sql_add1);
                    }else if($type=="0"){
                        $res_add1="1";
                    }
                } else if ($row_find1['m'] == "1") {
                    if($type=="1"){
                        $res_add1 = "1";
                    }else if($type=="0"){
                        $sql_add1 = "update `$userid` set `m`='0' where `userid`=$followed";
                        $res_add1 = $dbh->exec($sql_add1);
                    }
                }
            }
        }
        if (isset($res_add1)) {} else {
            if($type=="1") {
                $sql_add1 = "insert `$userid` values(null,'$followed','1','0')";
                $res_add1 = $dbh->exec($sql_add1);
            }else if($type=="0"){
                $res_add1="1";
            }
        }
        $sql_find2 = "select*from `$followed`";
        $res_find2 = $dbh->query($sql_find2);
        while ($row_find2 = $res_find2->fetch()) {
            if ($row_find2['userid'] == $userid) {
                if ($row_find2['y'] == "0") {
                    if($type=="1") {
                        $sql_add2 = "update `$followed` set `y`='1' where `userid`=$userid";
                        $res_add2 = $dbh->exec($sql_add2);
                    }else if($type=="0"){
                        $res_add2="1";
                    }
                } else if ($row_find2['y'] == "1") {
                    if($type=="1") {
                        $res_add2 = "1";
                    }else if($type=="0"){
                        $sql_add2 = "update `$followed` set `y`='0' where `userid`=$userid";
                        $res_add2 = $dbh->exec($sql_add2);
                    }
                }
            }
        }
        if (isset($res_add2)) {} else {
            if($type=="1") {
                $sql_add2 = "insert `$followed` values(null,'$userid','0','1')";
                $res_add2 = $dbh->exec($sql_add2);
            }else if($type=="0"){
                $res_add2="1";
            }
        }
        if ($res_add1 || $res_add2) {
            echo json_encode(array("result" => "Y"));
        } else {
            echo json_encode(array("result" => "N", "msg" => "出错了"));
        }
    }
}else{
    echo json_encode(array("result"=>"N","msg"=>"未登录"));
}
