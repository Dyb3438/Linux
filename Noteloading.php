<?php
function userTextDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}
$noteid=$_GET['NoteId'];
$page=$_GET['Page'];
if(isset($_COOKIE['userid'])){
    $userid=$_COOKIE['userid'];
}else{
    $userid="";
}
include_once("pdo_db.php");
$sql = "select `id` from `forum`";
$res=$dbh->query($sql);
while($row=$res->fetch()){
    if($noteid==$row['id']) {
        $a = 1;
    }
}

if(isset($a)) {
//查找帖子标题和点击数
    $sql_check = "select `id` from `$noteid` order by `id` desc limit 1";
    $res_check = $dbh->query($sql_check);
    while ($row_check = $res_check->fetch()) {
        $pages = ceil($row_check['id'] / 25);
    }
    $sql_findname2 = "select `notename`,`userid` from `forum` where `id`=$noteid";
    $res_findname2 = $dbh->query($sql_findname2);
    while ($row_findname2 = $res_findname2->fetch()) {
        $notename =userTextDecode($row_findname2['notename']);
        $noteuser=$row_findname2['userid'];
    }
    if ($page <= $pages) {
        if ($page == 1) {
            $sql_findname = "select `clickid` from `forum` where `id`=$noteid";
            $res_findname = $dbh->query($sql_findname);
            while ($row_findname = $res_findname->fetch()) {
                $clickid = $row_findname['clickid'] + 1;
            }
//增加点击计数
            $sql_add = "update `forum` set `clickid`=$clickid where `id`=$noteid";
            $res_add = $dbh->exec($sql_add);
        }
        $number = $page * 25;
        $sql_findid = "select `id` from `$noteid` order by `id` asc limit $number";
        $res_findid = $dbh->query($sql_findid);
        $id = array();
        $i = 1;
        while ($row_findid = $res_findid->fetch()) {
            $id[$i] = $row_findid['id'];
            $i++;
        }
        $from = ($page - 1) * 25 + 1;
        $to = $page * 25;
        if($to>count($id)){
            $to=count($id);
        }
        $sql_find = "select*from`$noteid` where `id` between $id[$from] and $id[$to] order by `id` asc ";
        $res_find = $dbh->query($sql_find);
        $floor = array();
        $i=$from;
        while ($row_find = $res_find->fetch()) {
            $praise = $row_find['praise'];
            $praiser = explode(",", $praise);
            if (in_array($userid, $praiser)) {
                $dianzan = "1";
                $dianzanid = count($praiser)-1;
            } else {
                $dianzan = "0";
                $dianzanid = count($praiser)-1;
            }
            if($userid==""){
                $dianzan="0";
            }
        $floorid = $row_find['id'];
        $userid2 = $row_find['userid'];
        $sql_finduser = "select `username`,`userphoto` from `users` where `userid`= $userid2 ";
        $res_finduser = $dbh->query($sql_finduser);
        while ($row_finduser = $res_finduser->fetch()) {
            $username = $row_finduser['username'];
            $userphoto = $row_finduser['userphoto'];
            $floor[$i] = array(
                "floor" => $floorid,
                "content" => userTextDecode($row_find['floorcontent']),
                "username" => $username,
                "userphoto" => $userphoto,
                "userid" => $userid2,
                "time" => $row_find['time'],
                "quoter" => $row_find['quoter'],
                "praiseid" => $dianzanid,
                "T-Fpraise" => $dianzan
            );
        }
        $i++;
    }
    for($a=$from;$a<=$to;$a++){
        if ($floor[$a]['quoter'] != 0) {
            $quoterid = $floor[$a]['quoter'];
            $sql_quoter = "select `floorcontent`,`userid`,`time` from `$noteid` where `id`=$quoterid ";
            $res_quoter = $dbh->query($sql_quoter);
            while ($row_quoter = $res_quoter->fetch()) {
                $userid = $row_quoter['userid'];
                include_once('pdo_db.php');
                $sql = "select `username`,`userphoto` from `users` where `userid`= $userid ";
                $res = $dbh->query($sql);
                while ($row = $res->fetch()) {
                    $username = $row['username'];
                    $userphoto = $row['userphoto'];
                    $floor[$a]['quoter'] = array(
                        "content" => userTextDecode($row_quoter['floorcontent']),
                        "time" => $row_quoter['time'],
                        "quoterid" => $floor[$a]['quoter'],
                        "username" => $username,
                        "userphoto" => $userphoto,
                        "userid" => $userid
                    );
                }
            }
            if(isset($floor[$a]['quoter']['time'])){}else{
                $floor[$a]['quoter']=array("content"=>"<font style='color:red'>内容已被删除！</font>");
            }
        }
    }
        echo json_encode(array("floor" => $floor, "pages" => $pages, "notename" => $notename));
        if(isset($_COOKIE['userid'])) {
            if ($_COOKIE['userid'] == $noteuser) {
                include_once("pdo_usersmoment.php");
                $sql_findif = "select `id`,`comment` from `$noteuser` where `comment` like 'new-$noteid-%'";
                $res_findif = $dbh->query($sql_findif);
                while ($row_findif = $res_findif->fetch()) {
                    $comment = $row_findif['comment'];
                    $id2 = $row_findif['id'];
                }
                if (isset($comment)) {
                    $sql_delectmoment = "DELETE FROM `$noteuser` WHERE `$noteuser`.`id` = $id2";
                    $res_delectmoment = $dbh->exec($sql_delectmoment);
                }
            }
        }











    } else {
        echo json_encode(array("result" => "N"));
    }
}else{
    echo json_encode(array("result"=>"N"));
}