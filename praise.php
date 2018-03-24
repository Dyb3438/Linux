<?php
if(isset($_COOKIE['userid'])){
    $userid=$_COOKIE['userid'];
    $noteid=$_POST['Noteid'];
    $floor=$_POST['Floor'];
    $t_f=$_POST['Type'];
    include_once("pdo_db.php");
    $sql_select="select `praise` from `$noteid` where `id`='$floor'";
    $res_select=$dbh->query($sql_select);
    while($row_select=$res_select->fetch()){
        $praise=$row_select['praise'];
    }
    $praiser=explode(",", $praise);
    if(in_array($userid, $praiser)) {
        if($t_f=="1"){
            $str="/(.*)(,$userid)(?!\w)(.*)/u";
            preg_match_all($str,$praise,$output);
            $praise2=$output[1][0].$output[3][0];
            $sql = "update `$noteid` set `praise` = '$praise2' where `id`='$floor'";
            $res = $dbh->exec($sql);
            if ($res) {
                $result = "Y";
            } else {
                $result = "N";
            }
        }else{
            $result="Y";
        }
    }else{
        if ($t_f == "0") {
            $praise2 = $praise . "," . $userid;
            $sql = "update `$noteid` set `praise` = '$praise2' where `id`='$floor'";
            $res = $dbh->exec($sql);
            if ($res) {
                $result = "Y";
            } else {
                $result = "N";
            }
        }else{
            $result="Y";
        }
    }
    echo $result;
}else{
    echo "N";
}
