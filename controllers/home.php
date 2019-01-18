<?php
require_once 'db.php';

class homeController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();

        $this->updateDisCache();

        $sql = $this->db->prepare("SELECT TripID, Trip.Date, Distance, (SELECT TripData.Timestamp FROM TripData WHERE TripData.TripID = Trip.TripID ORDER BY TripData.Timestamp DESC LIMIT 1) AS LastDate FROM Trip ORDER BY Trip.Date DESC");
        $status = $sql->execute(array());
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);

        require_once './views/home.php';
    }

    private function updateDisCache(){
        $sql = $this->db->prepare("SELECT * FROM Trip WHERE Trip.Distance = 0");
        $status = $sql->execute(array());
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $tripitem) {
            $distance = 0;
            $sql = $this->db->prepare("SELECT * FROM TripData WHERE TripID = ? ORDER BY Timestamp");
            $sql->execute(array($tripitem["TripID"]));

            $tripdata = $sql->fetchAll(PDO::FETCH_ASSOC);
            foreach ($tripdata as $id => $item) {
                if ($item["Lat"] != 0 && $item["Lon"] != 0 && $id != sizeof($tripdata) - 1) {
                    $distance += $this->haversineGreatCircleDistance($item["Lat"], $item["Lon"], $tripdata[$id + 1]["Lat"], $tripdata[$id + 1]["Lon"]);
                }
            }
            $distance = round($distance / 1000, 2);
            $sql = $this->db->prepare("UPDATE Trip SET Distance=? WHERE TripID = ?");
            $sql->execute(array($distance,$tripitem["TripID"]));
        }
    }


    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}

?>