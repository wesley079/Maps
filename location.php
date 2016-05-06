<?php
//global variables
$locationData = [];

//check if filled in data is correct
if (isset($_GET['srch'])) {
    //get search query
    $searchCountry = $_GET['srch'];

    //get locations
    $getLocation = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . $searchCountry);

    //decode json file
    $foundLocations = json_decode($getLocation);

    //check if there is a result or not
    if ($foundLocations->status == "ZERO_RESULTS") {
        //tell json file there is no found data
        $locationData = "NO_LOCATIONS";
    }
    //if there is one or more found location
    elseif ($foundLocations->status == "OK") {

        //get all found locations and their coordinates
        foreach ($foundLocations->results as $location) {
            //create object
            $locationInfo = new stdClass();

            //add relevant info to the object
            $locationInfo->address = $location->formatted_address;
            $locationInfo->lat = $location->geometry->location->lat;
            $locationInfo->lng = $location->geometry->location->lng;

            //add objects to the array
            $locationData[] = $locationInfo;
        }
    }
    //if an unknown error shows up
    else {
        $locationData = "error";
    }
} else {
    echo 'error';
}
//make a json file from the array
header('Content-Type: application/json');
echo json_encode($locationData);