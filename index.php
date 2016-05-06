<?php
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8"/>
    <title></title>
    <link href="css/style.css" type="text/css" rel="stylesheet"/>

</head>
<body>
<header>
<form>
    <input id="citySearch" type="text" value="" placeholder="city/country/zipcode/street"/>
</form>
</header>
<div id="foundPlaces"></div>
<div id="map-canvas"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script src="js/main.js"></script>
<!--    Add your accestoken here-->
<script
    src="https://api.instagram.com//v1/media/search?access_token=<!!!!ADD HERE!!!!!>&lat=52.060669&lng=4.494025&distance=4000%3Fclient_id%3D793f0e371a104f97a78e4db95f242ea9&%3Faccess_token=ead7fdb2cf5747fbbeac43f08e7491ac%20HTTP/1.1"></script>
</body>
</html>