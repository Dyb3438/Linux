<?php
if(isset($_COOKIE['userid'])){
    setcookie("userid","");
    echo json_encode(array("result"=>"Y"));
}else{
    echo json_encode(array("result"=>"N"));
}