//global vars
var mapOptions = '';
var map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);
var openMarker = '';
var foundPlaces = [];

//load content
$(init);

/**
 * Get the content to load when the application starts
 */
function init() {
    initialize();

    //Ajax call for input + disable the enter button to reload the page
    $('#citySearch').on('keyup', searchCityHandler).keypress(function(e) {
        if (e.which == 13) return false;
    });

    //get the instagram API as a json file
    $.getJSON("decoder.php", loadInstagram);

    //Event handler for selected city
    $('#foundPlaces').on('click','.search' ,foundPlacesHandler);


}
/**
 * Add standard options for the map
 */
function initialize() {

    //standard focus on Rotterdam - The Netherlands
    mapOptions = {
        center: {lat: 51.9244201, lng: 4.4777325},
        zoom: 15
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

}
/**
 * Get all the pictures from the json file
 * @param data
 */
function loadInstagram(data) {
    //get all pictures & create markers for each of them
    $.each(data, function (i, val) {
        placeMarker(i, val)
    })
}
/**
 * Place a marker for all found instagram pictures
 * @param i
 * @param val
 */
function placeMarker(i, val) {

    var icon = new google.maps.MarkerImage(
        val.user.profile_picture, //url
        new google.maps.Size(80, 80) //size 80px x 80px
    );

    //add a marker named to the unique number 'i'
    i = new google.maps.Marker({
        position: {lat: val.location.latitude, lng: val.location.longitude},
        map: map,
        icon: icon,
        animation: google.maps.Animation.DROP,
        title: val.user.full_name
    });
    //Get all different tags and place a # in front of them
    var tags = [];
    $.each(val.tags, function (counter, val) {
        tags.push('#' + val);
    });

    //make the info window to display when clicked on the marker
    var contentString = '<div id="infoWindow">' +
        '<h1 id="firstHeading" class="firstHeading">'+val.text+'</h1>' +

        '<p id="tags">' + tags + '</p>' +
        '<img src="' + val.images + '" alt="'+"Instagram picture from: "+val.user.username+' class="image"/>' +
        '</div>';

    //add an info window for each marker
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    //make the marker 'i' clickable
    google.maps.event.addListener(i, 'click', function () {

        //check if there is another open marker
        if (!(openMarker == "")) {
            openMarker.close();

        }

        //open the clicked marker
        infowindow.open(map, i);
        i.setAnimation(google.maps.Animation.DROP);
        //Fill variable openMarker with
        openMarker = infowindow;

    });
}
/**
 * Getting feedback for the user so he can select a city
 */
function searchCityHandler(){
    $.ajax({
        dataType: "json",
        url: 'location.php/?srch='+this.value,
        success: getPlaces
    });
}

/**
 * Tells users all the options found by an extern API (see location.php)
 * @param data
 */
function getPlaces(data){

    $('#foundPlaces').fadeTo('slow', 0.9).empty();
    foundPlaces = [];

    $.each(data, function (i, val) {

        //save location for each option
        var lngLat = [];
        lngLat.push(val.lng);
        lngLat.push(val.lat);
        foundPlaces[val.address] = lngLat;

        //add a div with the city
        $('#foundPlaces').append('<div class="search">'+val.address+'</div>');
    });
}
/**
 * Loading the selected place with the new pictures
 */
function foundPlacesHandler(){
    //fadeout the option menu
    $('#foundPlaces').fadeOut('fast');

    //save long & latitude
    var long = foundPlaces[this.innerHTML][0];
    var lat = foundPlaces[this.innerHTML][1];

    //set new center point
    mapOptions = {
        center: {lat: lat, lng: long},
        zoom: 15
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    //new place is selected so empty array
    foundPlaces = [];

    //get the new data the user wants
    $.ajax({
        dataType: "json",
        url: 'decoder.php?lat='+lat+'&lng='+long,
        success: loadInstagram
    });

}

