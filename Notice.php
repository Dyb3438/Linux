<?php
function userTextDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}
include_once("pdo_db.php");
date_default_timezone_set("Asia/Shanghai");
$time=date("Y-m-d H:i:s",time()-5*24*3600);
$sql="select*from `forum-manage` where `time`>'$time' order by `time` desc";
$res=$dbh->query($sql);
$notice=array();
$i=1;
while($row=$res->fetch()){
    $notice[$i]=array(
        "userid"=>$row['userid'],
        "thing"=>userTextDecode($row['thing']),
        "image"=>userTextDecode($row['image']),
        "time"=>$row['time']
    );
    $i++;
}
for($a=1;$a<$i;$a++){
    $thing=$notice[$a]['thing'];
    $str="/^delete_([a-z]{1,})-(\w+)-(.+)$/u";
    preg_match_all($str,$thing,$output);
    if($output[1][0]=="note"){
        echo "管理员(<font style='color:blue'>".$notice[$a]['userid']."</font>)于 ".$notice[$a]['time']." 删除了 <font style='color:red'>".$output[2][0]."</font> 的帖子【 <font style='color:red'>".$output[3][0]."</font> 】<br>";
        echo "删除原因：".$notice[$a]['image']."<br>";
    }else if($output[1][0]=="floor"){
        echo "管理员(<font style='color:blue'>".$notice[$a]['userid']."</font>)于 ".$notice[$a]['time']." 删除了帖子【 <font style='color:red'>".$output[3][0]."</font>】的<font style='color:red'> ".$output[2][0]." </font>楼<br>";
        echo "删除原因：".$notice[$a]['image']."<br>";
    }
}