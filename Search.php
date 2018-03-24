<?php
$search=$_POST['Search'];
$key=explode(" ",$search);
$get_key=array();
$a=1;
for($i=0;$i<count($key);$i++){
    if($key[$i]!=null){
        $get_key[$a]="'%".$key[$i]."%' and `notename` LIKE ";
        $a++;
    }
}
$result=implode($get_key);
$result1=rtrim($result," and `notename` LIKE ");
include_once("pdo_db.php");
$sql="SELECT `id`,`userid`,`notename`,`time` FROM `forum` WHERE `notename` LIKE $result1 collate  `utf8_unicode_ci` order by `clickid` desc limit 25";
$res=$dbh->query($sql);
$search_result=array();
$id=1;
while($row=$res->fetch()){
    $search_result[$id]=array(
        "noteid" => $row['id'],
        "notename" => $row['notename'],
        "time" => $row['time'],
        "userid" => $row['userid']
    );
    $id++;
}
if ($id!=1) {
    echo json_encode(array("result"=>"Y","search" => $search_result));
}else{
    echo json_encode(array("result"=>"N"));
}