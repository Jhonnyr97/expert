<?php


if(function_exists($_GET['f'])) {
    $_GET['f']();
 }

 function json_all(){
    $data = file_get_contents("date.json");
    $img = json_decode($data, true);
    
    header('Content-type: application/json');
    echo json_encode($img);
 }

function hello(){
    echo "hello";
}

?>

