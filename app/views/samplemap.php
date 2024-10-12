<?php include "partials/clientheader.php" ?>
<!DOCTYPE html>
<html>
<head>
   <title>CatWiki Map</title>
   <!-- Leaflet CSS -->
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
   <style>
      #map {
         height: 400px;
         width: 100%;
      }
   </style>
</head>
<body>

<h3>Shelters Map</h3>
<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
   // Check if the browser supports Geolocation
   if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
         // Get the current coordinates
         var lat = position.coords.latitude;
         var lon = position.coords.longitude;

         // Initialize the map and set it to the user's current location
         var map = L.map('map').setView([lat, lon], 13);

         // Add OSM tile layer
         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
         }).addTo(map);

         // Add a marker at the current location
         L.marker([lat, lon]).addTo(map)
            .bindPopup('You are here!')
            .openPopup();

      }, function(error) {
         alert('Unable to retrieve your location. Make sure location services are enabled.');
      });
   } else {
      alert('Geolocation is not supported by your browser.');
   }
</script>

</body>
</html>