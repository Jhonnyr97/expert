<?php

// get all img

if(function_exists($_GET['f'])) {
    $_GET['f']();
 }

 function get_course_by_user(){
    $data = file_get_contents("date.json");
    $imgs = json_decode($data, true);
    $flag = $_GET['id'];
    $flag_course = $_GET['courseid'];

    $resources = array_filter($imgs, function ($var) use ($flag, $flag_course) {
        return ($var['userid'] == $flag && $var['courseid'] == $flag_course );
    });
    $resources = array_values($resources);

    header('Content-type: application/json');
    echo json_encode($resources);
 }
 function unsetValue(array $array, $value, $strict = TRUE){
     if(($key = array_search($value, $array, $strict)) !== FALSE) {
         unset($array[$key]);
     }
     return $array;
 }

 function delete_img(){
    $data = file_get_contents("date.json");
    $imgs = json_decode($data, true);
    $flag = $_GET['id'];

    $resources = array_filter($imgs, function ($var) use ($flag) {
        return ($var['id'] != $flag);
    });
    $resources = array_values($resources);
    unlink("{$flag}.jpeg");

    file_put_contents('date.json', json_encode($resources));

    header('Content-type: application/json');
    echo json_encode($resources);
 }

function get_course(){
    $data = file_get_contents("date.json");
    $imgs = json_decode($data, true);
    $flag = $_GET['id'];

    $resources = array_filter($imgs, function ($var) use ($flag) {
        return ($var['courseid'] == $flag);
    });

    $resources = array_values($resources);

    header('Content-type: application/json');
    echo json_encode($resources);

 };

 function create_img($fname, $id) {
    $base64DataString = $fname;
    $pattern = '/data:image\/(.+);base64,(.*)/';
    preg_match($pattern, $base64DataString, $matches);
    // image file extension
    $imageExtension = $matches[1];

    // base64-encoded image data
    $encodedImageData = $matches[2];

    // decode base64-encoded image data
    $decodedImageData = base64_decode($encodedImageData);
    file_put_contents("{$id}.{$imageExtension}", $decodedImageData);
    return "https://youniversity2.expert-italia.it/plugin_gallery/{$id}.{$imageExtension}";
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
        $percent = 1;
        $width = imagesx($im);
        $height = imagesy($im);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        $img = imagecreatetruecolor($newwidth,$newheight);
        imagecopyresized($img,$im,0,0,0,0,$newwidth,$newheight,$width,$height);
        ob_start();
        imagejpeg($img);
        $imagedata = ob_get_clean();
        $elem["data"] = create_img("data:image/jpeg;base64,".base64_encode($imagedata), $elem["id"]);
        //$elem["data"] = "data:image/jpeg;base64,".base64_encode($imagedata);
        imagedestroy($img);
        array_push($imgs, $elem);
    };

    //header('Content-type: application/json');
    $newJsonString = json_encode($imgs);
    file_put_contents('date.json', $newJsonString);
    echo $newJsonString;
}



?>

