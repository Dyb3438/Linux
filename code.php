<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");

session_start();
$yzm="";
for($i=0;$i<4;$i++)
{
    $yzm.=dechex(rand(0,15));
}

$_SESSION["code"]=$yzm;
 $_width=75;
 $_height=25;
 $_img=imagecreatetruecolor($_width,$_height);
 
 $_white=imagecolorallocate($_img,255,255,255);
 imagefill($_img,0,0,$_white);
 
 $_black=imagecolorallocate($_img,100,100,100);
 imagerectangle($_img,0,0,$_width-1,$_height-1,$_black);
 
 for($i=0;$i<6;$i++)
 {
    $_rnd_color=imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    imageline($_img,mt_rand(0,75),mt_rand(0,75),mt_rand(0,75),mt_rand(0,75),$_rnd_color);
 }
 
 
 for($i=1;$i<100;$i++)
 {
    imagestring($_img,1,mt_rand(1,$_width),mt_rand(1,$_height),"*",
    imagecolorallocate($_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255)));
 }
 
 for($i=0;$i<strlen($_SESSION['code']);$i++)
 {
    imagestring($_img,mt_rand(3,5),$i*$_width/4+mt_rand(1,10),
    mt_rand(1,$_height/2),$_SESSION['code'][$i],
    imagecolorallocate($_img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200)));
 }
 
 
 header('Content-Type:image/png');
 imagepng($_img);
 imagedestroy($_img);
 
 
 
?>