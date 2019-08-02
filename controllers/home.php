<?php
require_once 'db.php';

class homeController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();

        $this->updateCache();

        $sql = $this->db->prepare("SELECT TripID, Trip.Date, Distance, LastDate FROM Trip ORDER BY Trip.Date DESC");
        $status = $sql->execute(array());
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);

        require_once './views/home.php';
    }

    private function updateCache(){
        $sql = $this->db->prepare("SELECT * FROM Trip WHERE Trip.Cached = 0");
        $status = $sql->execute(array());
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $tripitem) {
            $distance = 0;
            $sql = $this->db->prepare("SELECT (sum(Speed/60/60)) as Distance, (SELECT TripData.Timestamp FROM TripData WHERE TripData.TripID = ? ORDER BY TripData.Timestamp DESC LIMIT 1) AS LastDate FROM TripData WHERE TripID = ?");
            $sql->execute(array($tripitem["TripID"],$tripitem["TripID"]));

            $tripdata = $sql->fetch(PDO::FETCH_ASSOC);
          
            $distance = round($tripdata["Distance"], 2);
            $sql = $this->db->prepare("UPDATE Trip SET Distance=?, LastDate=?, Cached=1 WHERE TripID = ?");
            $sql->execute(array($distance,$tripdata["LastDate"],$tripitem["TripID"]));
        }
    }

}

?>