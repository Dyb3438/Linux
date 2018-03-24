<?php
if(isset($_COOKIE['userid'])){
    include_once("pdo_usersdb.php");
    $userid=$_COOKIE['userid'];
    $sql_find="select*from `$userid`";
    $res_find=$dbh->query($sql_find);
    $fans=array();
    $following=array();
    $a=0;
    $i=0;
    while($row_find=$res_find->fetch()){
        if($row_find['m']=="1"){
            $a++;
            $following[$a]=$row_find['userid'];
        }
        if($row_find['y']=="1"){
            $i++;
            $fans[$i]=$row_find['userid'];
        }
    }
    include_once("pdo_db.php");
    if($_GET['type']=="1"){
        for($c=1;$c<=$a;$c++){
            $followed=$following[$c];
            $sql="select `username`,`userphoto` from `users` where `userid`='$followed'";
            $res=$dbh->query($sql);
            while($row=$res->fetch()){
                $following[$c]=array(
                    "userid"=>$followed,
                    "username"=>$row['username'],
                    "userphoto"=>$row['userphoto']
                );
            }
        }
        echo json_encode(array("following"=>$following));
    }else if($_GET['type']=="2"){
        for($c=1;$c<=$i;$c++){
            $followed=$fans[$c];
            $sql="select `username`,`userphoto` from `users` where `userid`='$followed'";
            $res=$dbh->query($sql);
            while($row=$res->fetch()){
                $fans[$c]=array(
                    "userid"=>$followed,
                    "username"=>$row['username'],
                    "userphoto"=>$row['userphoto']
                );
            }
        }
        echo json_encode(array("fans"=>$fans));
    }
}


