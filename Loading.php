<?php
function userTextDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}
include_once("pdo_db.php");
$page=$_GET['Page'];
$sql="select `id` from `forum` order by `id` desc limit 1";
$res=$dbh->query($sql);
while($row=$res->fetch()){
    $pages=ceil($row['id']/25);
}
if(isset($pages)) {
    if ($page <= $pages) {
//找置顶帖
        if ($page == "1") {
            $findtop = "select `id`,`userid`,`notename` from `forum` where `T-Ftop` = 1 order by `last-response-time` desc";
            $find = $dbh->query($findtop);
            $top = array();
            $i = 1;
            while ($row = $find->fetch()) {
                $top[$i] = array(
                    "noteid" => $row['id'],
                    "userid" => $row['userid'],
                    "notename" => userTextDecode($row['notename'])
                );
                $i++;
            }
//其余帖子按照发布时间倒序排序(即最新的在上面)
            $number_extra = 25 - count($top);
            $sql_extra = "select*from `forum` where `T-Ftop` != 1 order by `last-response-time` desc limit $number_extra";
            $find_extra = $dbh->query($sql_extra);
            $extra = array();
            $i = 1;
            while ($row_extra = $find_extra->fetch()) {
                $userid = $row_extra['userid'];

                include_once('pdo_db.php');
                $sql = "select `username`,`userphoto` from `users` where `userid`= $userid ";
                $res = $dbh->query($sql);
                while ($row = $res->fetch()) {
                    $username = $row['username'];
                    $userphoto = $row['userphoto'];
                    $extra[$i] = array(
                        "noteid" => $row_extra['id'],
                        "notename" => userTextDecode($row_extra['notename']),
                        "time" => $row_extra['time'],
                        "username" => $username,
                        "userphoto" => $userphoto,
                        "userid" => $userid
                    );
                }
                $i++;
            }
        } else {
            $findtop = "select `id` from `forum` where `T-Ftop` = 1 order by `id` desc";
            $find = $dbh->query($findtop);
            $top = array();
            $i = 1;
            while ($row = $find->fetch()) {
                $top[$i] = $row['id'];
                $i++;
            }
            $number = $page * 25 - count($top);
            $sql_extraid = "select `last-response-time` from `forum` where `T-Ftop` != 1 order by `last-response-time` desc limit $number";
            $find_extraid = $dbh->query($sql_extraid);
            $extraid = array();
            $i = 1;
            while ($row_extraid = $find_extraid->fetch()) {
                $extraid[$i] = $row_extraid['last-response-time'];
                $i++;
            }
            $end = count($extraid);
            $from = ($page - 1) * 25 + 1 - count($top);
            $sql_extra = "select*from `forum` where `last-response-time` between '$extraid[$end]' and '$extraid[$from]' and `T-Ftop` != 1 order by `last-response-time` desc";
            $find_extra = $dbh->query($sql_extra);
            $extra = array();
            $i = 1;
            while ($row_extra = $find_extra->fetch()) {
                $userid = $row_extra['userid'];

                include_once('pdo_db.php');
                $sql = "select `username`,`userphoto` from `users` where `userid`= $userid ";
                $res = $dbh->query($sql);
                while ($row = $res->fetch()) {
                    $username = $row['username'];
                    $userphoto = $row['userphoto'];
                    $extra[$i] = array(
                        "noteid" => $row_extra['id'],
                        "notename" => userTextDecode($row_extra['notename']),
                        "time" => $row_extra['time'],
                        "username" => $username,
                        "userphoto" => $userphoto,
                        "userid" => $userid
                    );
                }
                $i++;
            }
        }
        echo json_encode(array("top" => $top, "note" => $extra, "pages" => $pages));
    } else {
        echo json_encode(array("result" => "N"));
    }
}else{
    echo json_encode(array("top"=>"","note"=>"","pages"=>"1 "));
}
//计算页数

//返回


