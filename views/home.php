<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashcam - Trips</title>
    <?php include_once('head.php') ?>

</head>
<body>

<?php include_once('navigation.php') ?>

<div class="topMenuSecond">
    <div class="topMenuInner">
        <div class="topMenuTitle">Car / <b>Trip History</b></div>
    </div>
</div>

<div class="content">
    <?php foreach ($data as $trip) : ?>
    <a href="trip/<?=$trip["TripID"]?>"> <div class="contentBlock">
        <div class="contentBlockTitle"><?=date("F jS, Y - G:i", strtotime($trip["Date"]))?></div>
        <div>
            <div class="gauge gaugeSmall"><i class="mdi mdi-navigation "></i> <?=$trip["Distance"]?> Km</div>
            <div class="gauge gaugeSmall"><i class="mdi mdi-clock "></i> <?=gmdate("H:i:s", strtotime($trip["LastDate"]) - strtotime($trip["Date"]))?></div>
            <a onclick="if (confirm('Are you sure you want to delete this Trip?')) { window.location='trip/delete/?id=<?=$trip["TripID"]?>' }"><div class="deleteButton"><i class="mdi mdi-delete "></i></div>
            </a></div>
    </div> </a>
    <?php endforeach; ?>

</div>


</body>
</html>
