<?php
include_once('Functions.php');
$getId = $_GET["userId"];
$mysqli = connect();
$ins = 'select * from posts where userId='.$getId.' order by id ASC LIMIT 5 OFFSET 5';
$res = $mysqli->query($ins);
$postsarr = array();
$i = 0;
while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
    $postsarr[$i] = array("id"=>$row[0], "title"=>$row[1], "body"=>substr($row[2],0,80));
    $i++;
}
mysqli_free_result($res);
if ($postsarr != 0) {
    echo json_encode($postsarr);
}
else{
    echo json_encode("bad");
}