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
include_once("pdo_db.php");
date_default_timezone_set("Asia/Shanghai");
$time=date("Y-m-d H:i:s");
$content=userTextEncode($_POST['Content']);
$quoter=$_POST['Floor'];
$noteid=$_POST['Noteid'];
if(isset($_COOKIE['userid'])){
    $userid=$_COOKIE['userid'];
    $sql="INSERT INTO `$noteid` (`id`, `floorcontent`, `userid`, `quoter`, `time`, `praise`) VALUES (NULL, '$content', '$userid', '$quoter', '$time', NULL)";
    $sql2="update `forum` set `last-response-time`='$time' where `id`=$noteid";
    $res=$dbh->exec($sql);
    $res2=$dbh->exec($sql2);
    if($res){
        $result="Y";
        $msg="";
    }else{
        $result="N";
        $msg="录入数据库失败";
    }

    $sql_user="select `userid` from `forum` where `id`=$noteid";
    $res_user=$dbh->query($sql_user);
    while($row_user=$res_user->fetch()){
        $noteuser=$row_user['userid'];
    }
    if($quoter!=0) {
        $sql_findquoter = "select `userid` from `$noteid` where `id`=$quoter";
        $res_findquoter = $dbh->query($sql_findquoter);
        while ($row_findquoter = $res_findquoter->fetch()) {
            $quoter_userid = $row_findquoter['userid'];
        }
        $sql_findnote="select `id` from `$noteid` order by `id` desc limit 1";
        $res_findnote=$dbh->query($sql_findnote);
        $lastest_noteid=$res_findnote->fetch()['id'];
        if ($quoter_userid != $userid) {
            include_once("pdo_usersmoment.php");
            $response = $userid . "-" . $noteid . "-" . $lastest_noteid;
            $sql_addmoment = "insert into `$quoter_userid` values(null,'$response',null,null,'$time')";
            $res_addmoment = $dbh->exec($sql_addmoment);
        }
    }
    if($noteuser!=$userid) {
        include_once("pdo_usersmoment.php");
        $sql_findif="select `id`,`comment` from `$noteuser` where `comment` like 'new-$noteid-%'";
        $res_findif=$dbh->query($sql_findif);
        while ($row_findif = $res_findif->fetch()) {
            $comment = $row_findif['comment'];
            $id = $row_findif['id'];
        }
        if (isset($comment)) {
            $str = "/^new-$noteid-(\w+)/u";
            preg_match_all($str, $comment, $output);
            $number = $output[1][0];
            $number = $number + 1;
            $sql_delectmoment = "DELETE FROM `$noteuser` WHERE `$noteuser`.`id` = $id";
            $res_delectmoment = $dbh->exec($sql_delectmoment);
            $sql_addmoment = "insert into `$noteuser` values(null,null,'new-$noteid-$number',null,'$time') ";
            $res_addmoment = $dbh->exec($sql_addmoment);
        } else {
            $sql_addmoment = "insert into `$noteuser` values(null,null,'new-$noteid-1',null,'$time') ";
            $res_addmoment = $dbh->exec($sql_addmoment);
        }
    }
}else{
    $result="N";
    $msg="未登录";
}
echo json_encode(array("result"=>$result,"msg"=>$msg));