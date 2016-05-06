<?php
//add your access token
$accessToken = "";


$getApi = file_get_contents("https://api.instagram.com//v1/media/search?access_token=.$accesToken.&lat=51.9244201&lng=4.4777325&distance=5000%3Fclient_id%3D793f0e371a104f97a78e4db95f242ea9&%3Faccess_token=ead7fdb2cf5747fbbeac43f08e7491ac%20HTTP/1.1");
if(isset($_GET['lat']) && isset($_GET['lng'])){
    $getApi = file_get_contents("https://api.instagram.com//v1/media/search?access_token=.$accesToken.&lat=".$_GET['lat']."&lng=".$_GET['lng']."&distance=5000%3Fclient_id%3D793f0e371a104f97a78e4db95f242ea9&%3Faccess_token=ead7fdb2cf5747fbbeac43f08e7491ac%20HTTP/1.1");
}

$pictures = json_decode($getApi);

$instagram = [];

foreach ($pictures->data as $picture) {

    //creating an object for the information
    $instaInfo = new stdClass();

    //add relevant info to use in the application

    //delete all hashtags & images out of the discription

    if(isset($picture->caption->text)) {
        $string = $picture->caption->text;
        //remove images
        $string = preg_replace('/[^(\x20-\x7F)]*/', '', $string);
        //remove hastags
        $pattern = '/#[^!]+/';
        //replace characters with empty space
        $discription = preg_replace($pattern, '', $string);

        //save for json file
        $instaInfo->text = $discription; //description
    }
    else{
        $instaInfo->text = '';
    }
    $instaInfo->images = $picture->images->standard_resolution->url; //image url
    $instaInfo->user = $picture->user; //user info
    $instaInfo->tags = $picture->tags; //tags of the picture
    $instaInfo->location = $picture->location; //location of the picture

    //add the object to an array
    $instagram[] = $instaInfo;
}

//make a json file from the array
header('Content-Type: application/json');
echo json_encode($instagram);

exit;