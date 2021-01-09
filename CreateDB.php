<?php
include_once('Functions.php');
if(!CheckTables()){
    CreateTables();
    $post = $_POST["myData"];
    $array = json_decode($post, true);
    $mysqli = connect();
    foreach ($array as $key => &$value) {
        $ins = 'insert into posts (title,body,userId) values("'.$value["title"].'","'.$value["body"].'","'.$value["userId"].'")';
        $mysqli->query($ins);
    }
    //$array=null;
    //$ins="";
    $mysqli->close();
    if ($array != 0) {
        //echo json_encode($array);
    }
    else{
        //echo json_encode("bad");
    }
}
    //echo json_encode("ok");
?>
