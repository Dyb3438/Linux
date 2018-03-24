<?php
function userTextDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}
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
        $sql_findtop="select `id` from `forum` where `T-Ftop`='1'";
        $res_findtop=$dbh->query($sql_findtop);
        $top_id=array();
        while($row_findtop=$res_findtop->fetch()){
            array_push($top_id,$row_findtop['id']);
        }
        if(count($top_id)=="2"){
            $sql_back="select `notename`,`userid`,`id` from `forum` where `id`='$top_id[0]' or `id`='$top_id[1]' order by `id` desc";
            $res_back=$dbh->query($sql_back);
            $top=array();
            $i=1;
            while($row_back=$res_back->fetch()){
                $top[$i]=array(
                    "noteid"=>$row_back['id'],
                    "notename"=>userTextDecode($row_back['notename']),
                    "userid"=>$row_back['userid']
                );
                $i++;
            }
            echo json_encode(array("result"=>"F","top"=>$top,"ready_top"=>$noteid));
        }else if(count($top_id)<2){
            $sql_set="update `forum` set `T-Ftop`='1' where `id`='$noteid'";
            $res_set=$dbh->exec($sql_set);
            if($res_set){
                echo json_encode(array("result"=>"Y"));
            }else{
                echo json_encode(array("result"=>"N","msg"=>"出错了"));
            }
        }
    }else{
        echo json_encode(array("result"=>"N","msg"=>"身份过低"));
    }
}else{
    echo json_encode(array("result"=>"N","msg"=>"未登录"));
}
