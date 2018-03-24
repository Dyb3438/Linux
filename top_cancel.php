<?php
if(isset($_COOKIE['userid'])){
    $userid=$_COOKIE['userid'];
    $noteid=$_POST['Noteid'];
    include_once("pdo_db.php");
    $sql_findpower="select `power` from `users` where `userid`='$userid'";
    $res_findpower=$dbh->query($sql_findpower);
    while($row_findpower=$res_findpower->fetch()){
        $power=$row_findpower['power'];
    }
    if($power!="0"){
        $sql="update `forum` set `T-Ftop`='0' where `id`='$noteid'";
        $res=$dbh->exec($sql);
        if($res){
            echo "Y";
        }else{
            echo "N";
        }
    }else{
        echo "N";
    }
}else{
    echo "N";
}