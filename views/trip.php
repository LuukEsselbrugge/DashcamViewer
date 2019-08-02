<!DOCTYPE html>
<html lang="en">
<head>
    <title>Trip Details</title>
    <?php include_once('head.php') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>

    <script>ID = "<?=$ID?>";</script>
</head>
<body onLoad="init()">

<?php include_once('navigation.php') ?>

<div class="topMenu">
    <div onclick="changeTab(this,'tab-details')" class="topMenuItem topMenuItemSelected"><i class="mdi mdi-information"></i> Details</div>
    <div onclick="changeTab(this,'tab-playback')" class="topMenuItem"><i class="mdi mdi-play"></i> Playback</div>
</div>

<div class="content tab-playback" style="display: none;">

    <div class="contentBlock">
        <video id="video" style="width: 100%;" controls><source src="<?=($_SERVER['REMOTE_ADDR'] == "192.168.0.1") ? "http://192.168.0.200/dashcam/" : "" ?>/video/<?=$_GET["uri2"]?>.mp4" type="video/mp4"></video>
    </div>

    <div class="contentBlock gaugeBlock">
        <div class="gauge"><i class="mdi mdi-clock mdi-24px"></i> <br> <span id="Time">00:00:00</span></div>
        <div class="gauge"><i class="mdi mdi-speedometer mdi-24px"></i> <br> <span id="Speed">0 Km/h</span></div>
        <div class="gauge"><i class="mdi mdi-engine mdi-24px"></i> <br> <span id="RPM">0 Rpm</span></div>
        <div class="gauge"><i class="mdi mdi-settings mdi-24px"></i> <br> <span id="Gear">0</span></div>
	<div class="gauge"><i class="mdi mdi-water mdi-24px"></i> <br> <span id="CTemp">Coolant 0</span></div>
	<div class="gauge"><i class="mdi mdi-fan mdi-24px"></i> <br> <span id="ATemp">Intake 0</span></div>
	<div class="gauge"><i class="mdi mdi-weather-windy mdi-24px"></i> <br> <span id="Throttle">0</span></div>
    
    </div>

    <div id="map" class="contentBlock" style="height: 300px"></div>
    <input id="followCar" type="checkbox" checked="true"/> Follow car

</div>

<div class="content tab-details">
    <div class="contentBlock gaugeBlock">
    <div class="gauge">
    <i class="mdi mdi-flag mdi-24px"></i><br> <span id="startLoc"></span></div> <div class="gauge-icon-only"><i class="mdi mdi-arrow-right mdi-24px"></i></div>
        <div class="gauge" style="width: 20%"><i class="mdi mdi-car mdi-24px"></i><br> <div id="tripTime">00:00:00</div> </div><div class="gauge-icon-only"><i class="mdi mdi-arrow-right mdi-24px"></i></div>
        <div class="gauge"><i class="mdi mdi-flag-checkered mdi-24px"></i><br> <span id="endLoc"></span></div>
    </div>
    <div class="contentBlock gaugeBlock">
        <div class="gauge"><i class="mdi mdi-speedometer mdi-24px"></i> <br> Avg. <span id="avgSpeed">0</span> Km/h</div>
        <div class="gauge"><i class="mdi mdi-speedometer mdi-24px"></i> <br> Top. <span id="topSpeed">0</span> Km/h</div>
        <div class="gauge"><i class="mdi mdi-engine mdi-24px"></i> <br> Top. <span id="topRPM">0</span> Rpm</div>
        <div class="gauge"><i class="mdi mdi-navigation mdi-24px"></i><br> Dis. <span id="tripDistance">0</span> Km</div>
        
    </div>
    <div class="speed-chart contentBlock"><div class="chartLabel"><i class="mdi mdi-speedometer"></i> Speed over time</div></div>
    <div class="RPM-chart contentBlock"><div class="chartLabel"><i class="mdi mdi-engine"></i> RPM over time</div></div>
    <div class="coolant-chart contentBlock"><div class="chartLabel"><i class="mdi mdi-water"></i> Coolant temp over time</div></div>
    <div class="air-chart contentBlock"><div class="chartLabel"><i class="mdi mdi-fan"></i> Intake air temp over time</div></div>
</div>

</body>
<script src="/views/js/main.js"></script>
</html>
