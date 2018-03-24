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
if(isset($_COOKIE['userid'])){
    $sender=$_COOKIE['userid'];
    $receiver=$_POST['Receiver'];
    $chattext=userTextEncode($_POST['ChatText']);
    date_default_timezone_set("Asia/Shanghai");
    $time=date("Y-m-d H:i:s");
    include_once("pdo_db.php");
    $sql="insert into `chat-image` values(null,'$sender','$receiver','$chattext','$time','0')";
    $res=$dbh->exec($sql);
    if($res){
        echo "Y";
    }else{
        echo "N";
    }
}