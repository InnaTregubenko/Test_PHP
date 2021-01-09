<?php
include_once('Functions.php');

$mysqli = connect();
$ins = 'select * from users';
$res = $mysqli->query($ins);
$usersarr = array();
//$i = 0;
while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
    $usersarr[] = array("id"=>$row[0], "first_name"=>$row[1], "last_name"=>$row[2],"phone"=>$row[3],"email"=>$row[4],);
    //$i++;
}
mysqli_free_result($res);
//json_encode($usersarr, JSON_FORCE_OBJECT);
if ($usersarr != 0) {
    echo json_encode($usersarr);
}
else{
    echo json_encode("bad");
}

?>
