<?php
function userTextDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}
$userid=$_GET['Userid'];
$page=$_GET['Page'];
include_once("pdo_db.php");
$sql_note="select `id`,`notename`,`notename`,`time` from `forum` where `userid`=$userid order by `id` desc";
$res_note=$dbh->query($sql_note);
$not=array();
$i=0;
while($row_note=$res_note->fetch()){
    $i++;
    $sql="select `username`,`userphoto` from `users` where `userid`=$userid";
    $res=$dbh->query($sql);
    while($row=$res->fetch()){
        $not[$i]=array(
            "noteid"=>$row_note['id'],
            "notename"=>userTextDecode($row_note['notename']),
            "time"=>$row_note['time'],
            "username"=>$row['username'],
            "userphoto"=>$row['userphoto'],
            "userid"=>$userid
        );
    }
}
$pages=ceil($i/15);
if($page<$pages){
    $note=array_slice($not,($page-1)*15,15,true);
}else if($page==$pages){
    $note=array_slice($not,($page-1)*15,$i-($page-1)*15,true);
}else{
    $note="";
}
echo json_encode(array("note"=>$note,"pages"=>$pages));