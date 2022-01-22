<?php

// get all img

if(function_exists($_GET['f'])) {
    $_GET['f']();
 }

function json_all(){
    $data = file_get_contents("date.json");
    $imgs = json_decode($data, true);
    
    //header('Content-type: application/json');
    //echo json_encode($imgs);
    echo $_GET['id'];
 }


// post new img

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = file_get_contents("date.json");
    $imgs = json_decode($data, true);

    //get body object
    $entityBody = file_get_contents('php://input');
    $bodyJson = json_decode($entityBody, true);

    foreach($bodyJson as $elem){
        $data = preg_replace('#data:image/[^;]+;base64,#', '', $elem["data"]);
        $data = base64_decode($data);
        $im = imagecreatefromstring($data);
        $percent = 0.15;
        $width = imagesx($im);
        $height = imagesy($im);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        $img = imagecreatetruecolor($newwidth,$newheight);
        imagecopyresized($img,$im,0,0,0,0,$newwidth,$newheight,$width,$height);
        ob_start();
        imagejpeg($img);
        $imagedata = ob_get_clean();
        $elem["data"] = "data:image/jpeg;base64,".base64_encode($imagedata);
        imagedestroy($img);
        array_push($imgs["images"], $elem);
    };

    //header('Content-type: application/json');
    $newJsonString = json_encode($imgs);
    if (file_put_contents('date.json', $newJsonString)){
        echo "ok";
    }else {
        echo "no ok";
    }
    echo $newJsonString;
}



?>

