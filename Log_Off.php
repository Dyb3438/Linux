<?php
session_start();
if(isset($_SESSION['userid'])&&$_SESSION['timeout']>time()){
    unset($_SESSION['userid']);
    unset($_SESSION['timeout']);
    echo json_encode(array("result"=>"Y"));
}else{
    echo json_encode(array("result"=>"N"));
}