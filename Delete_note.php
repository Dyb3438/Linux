<?php
function userTextEncode($str){
    if(!is_string($str))return $str;
    if(!$str || $str=='undefined')return '';

    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
        return addslashes(addslashes($str[0]));
    },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
    return json_decode($text);
}

if(isset($_COOKIE['userid'])) {
    $userid = $_COOKIE['userid'];
    $noteid = $_POST['Noteid'];
    $image=userTextEncode($_POST['Image']);
    date_default_timezone_set("Asia/Shanghai");
    $time=date("Y-m-d H:i:s");
    include_once("pdo_db.php");
    $sql_findpower="select `power` from `users` where `userid`='$userid'";
    $res_findpower=$dbh->query($sql_findpower);
    while($row_findpower=$res_findpower->fetch()){
        $power=$row_findpower['power'];
    }
    if (isset($_POST['Floor'])) {
        $floor = $_POST['Floor'];
        $sql_findnotename="select `notename` from `forum` where `id`='$noteid'";
        $res_findnotename=$dbh->query($sql_findnotename);
        while($row_findnotename=$res_findnotename->fetch()){
            $notename=$row_findnotename['notename'];
        }
        $sql_deletefloor="delete from `$noteid` where `id`='$floor'";
        $res_deletefloor=$dbh->exec($sql_deletefloor);
        if($res_deletefloor){
            if($power!="0") {
                $sql = "insert `forum-manage` values(null,'$userid','delete_floor-$floor-$notename','$image','$time')";
                $res = $dbh->exec($sql);
                if ($res) {
                    echo "Y";
                } else {
                    echo "N";
                }
            }else{
                echo"Y";
            }
        }else{
            echo "N";
        }
    } else {
        $sql_findnotename="select `notename`,`userid` from `forum` where `id`='$noteid'";
        $res_findnotename=$dbh->query($sql_findnotename);
        while($row_findnotename=$res_findnotename->fetch()){
            $notename=$row_findnotename['notename'];
            $noteuser=$row_findnotename['userid'];
        }
        $sql_delectnotename = "DELETE FROM `forum` where `id`='$noteid'";
        $res_delectnotename = $dbh->exec($sql_delectnotename);
        if ($res_delectnotename) {
            if($power!="0") {
                $sql = "insert `forum-manage` values(null,'$userid','delete_note-$noteuser-$notename','$image','$time')";
                $res = $dbh->exec($sql);
                if ($res) {
                    echo "Y";
                } else {
                    echo "N";
                }
            }else{
                echo "Y";
            }
        } else {
            echo "N";
        }
        $sql_delectnote = "DROP TABLE `$noteid`";
        $res_delectnote = $dbh->exec($sql_delectnote);
    }
}else{
    echo "N";
}