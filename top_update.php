<?php
if(isset($_COOKIE['userid'])){
    $userid=$_COOKIE['userid'];
    $top_id=$_POST['Top_id'];
    $cancel_id=$_POST['Cancel_id'];
    include_once("pdo_db.php");
    $sql_findpower="select `power` from `users` where `userid`='$userid'";
    $res_findpower=$dbh->query($sql_findpower);
    while($row_findpower=$res_findpower->fetch()){
        $power=$row_findpower['power'];
    }
    if($power!="0") {
        $sql_1 = "update `forum` set `T-Ftop`='0' where `id`='$cancel_id'";
        $res_1 = $dbh->exec($sql_1);
        if ($res_1) {
            $sql_2 = "update `forum` set `T-Ftop`='1' where `id`='$top_id'";
            $res_2 = $dbh->exec($sql_2);
            if ($res_2) {
                echo "Y";
            } else {
                echo "N";
            }
        } else {
            echo "N";
        }
    }else{
        echo "N";
    }
}else{
    echo "N";
}