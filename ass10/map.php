<?php

$ip = $_SERVER['REMOTE_ADDR'] ?? '8.8.8.8'; 

$apiURL = "https://ipinfo.io/{$ip}/json";
$response = @file_get_contents($apiURL);
$data = $response ? json_decode($response, true) : [];

$coords = isset($data['loc']) ? explode(',', $data['loc']) : ['52.52', '13.405']; 
$lat = floatval($coords[0]);
$lon = floatval($coords[1]);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Assignment 10 – Linked Services (SuperLeague)</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
  body { font-family: Arial, sans-serif; text-align:center; margin:20px; }
  #map { height: 500px; width: 80%; margin: auto; border: 2px solid #ccc; }
</style>
</head>
<body>
<h1>Client Location Lookup</h1>
<p>Your IP: <b><?=htmlspecialchars($ip)?></b></p>
<p>This map shows your approximate region, using data from <a href="https://ipinfo.io/" target="_blank">ipinfo.io</a>.</p>

<div id="map"></div>

<script>
const lat = <?=json_encode($lat)?>;
const lon = <?=json_encode($lon)?>;
const ip  = <?=json_encode($ip)?>;

const map = L.map('map').setView([lat, lon], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

const marker = L.marker([lat, lon]).addTo(map);
marker.bindPopup(`IP Address: <b>${ip}</b><br>Lat: ${lat}<br>Lon: ${lon}`).openPopup();
</script>

<p><a href="../index.html">Back to site</a></p>
</body>
</html>