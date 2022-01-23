<?php

// get all img

if(function_exists($_GET['f'])) {
    $_GET['f']();
 }
    function delete_course(){
        $course = $_GET['id'];
        $data = find_file("*", $course);
        $path = "*_{$course}.json";

        foreach ($data as $elem){
            echo "{$elem["id"]}.png";
            unlink("{$elem["id"]}.png");
        }

        foreach (glob($path) as $filename){
            echo $filename;
            unlink($filename);
        }


    }

    function find_file($user = '*', $course, $get_name = false){
        if ($get_name == false){
            $path = "{$user}_{$course}.json";
            $bigdata = [];
            foreach (glob($path) as $filename) {
                $data = file_get_contents($filename);
                $elem = json_decode($data, true);
                array_push($bigdata, $elem);
            } 
            $bigdata = array_merge(...$bigdata);
            return $bigdata;
        }else{
            $path = "*_*.json";
            $bigdata = [];
            foreach (glob($path) as $filename) {
                $data = file_get_contents($filename);
                $elem = json_decode($data, true);

                $resources = array_filter($elem, function ($var) use ($get_name) {
                    return ($var['id'] != $get_name);
                });
                $resources = array_values($resources);
                file_put_contents($filename, json_encode($resources));
            } 
            $bigdata = array_merge(...$bigdata);
            return $resources;
        }
    }

    function get_course_by_user(){
        //$data = file_get_contents("date.json");
        //$imgs = json_decode($data, true);
        $flag = $_GET['id'];
        $flag_course = $_GET['courseid'];
        $imgs = find_file($flag, $flag_course);

        $resources = array_filter($imgs, function ($var) use ($flag, $flag_course) {
            return ($var['userid'] == $flag && $var['courseid'] == $flag_course );
        });
        $resources = array_values($resources);

        header('Content-type: application/json');
        echo json_encode($resources);
    }

    function delete_img(){
        $flag = $_GET['id'];

        $resources = find_file("*", "*", $flag);

        unlink("{$flag}.png");

        header('Content-type: application/json');
        echo json_encode($resources);
    }

    function get_course(){
        //$data = file_get_contents("date.json");
        //$imgs = json_decode($data, true);
        $flag = $_GET['id'];
        $imgs = find_file("*", $flag);
        $resources = array_filter($imgs, function ($var) use ($flag) {
            return ($var['courseid'] == $flag);
        });

        $resources = array_values($resources);

        header('Content-type: application/json');
        echo json_encode($resources);

    };

    function create_file($user, $course){
        $name_file = "{$user}_{$course}.json";

        if(!file_exists($name_file)) {
            $file = fopen($name_file, "w");
            fwrite($file, "[]");
            fclose($file);
            chmod($file, 0777);
            $data = file_get_contents($name_file);
            $imgs = json_decode($data, true);
            
        }else {
            $data = file_get_contents($name_file);
            $imgs = json_decode($data, true);
        }
        return $imgs;
    }

    function saveBase64ImagePng($base64Image, $id){
        //set name of the image file

        $fileName =  "{$id}.png";

        $base64Image = trim($base64Image);
        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);

        $imageData = base64_decode($base64Image);
        //Set image whole path here 
        $filePath = $fileName;
        file_put_contents($filePath, $imageData);

        return "https://youniversity2.expert-italia.it/plugin_gallery/{$fileName}";
    }
    // post new img
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //$data = file_get_contents("date.json");
        //$imgs = json_decode($data, true);

        //get body object
        $entityBody = file_get_contents('php://input');
        $bodyJson = json_decode($entityBody, true);
        $imgs = create_file($bodyJson[0]["userid"], $bodyJson[0]["courseid"]);
        foreach($bodyJson as $elem){
            $elem["data"] = saveBase64ImagePng($elem["data"], $elem["id"]);
            //$elem["data"] = "data:image/jpeg;base64,".base64_encode($imagedata);
            imagedestroy($img);
            array_push($imgs, $elem);
        };

        //header('Content-type: application/json');
        $newJsonString = json_encode($imgs);
        $name_file = "{$bodyJson[0]["userid"]}_{$bodyJson[0]["courseid"]}.json";
        file_put_contents($name_file, $newJsonString);
        echo $newJsonString;
    }

?>

