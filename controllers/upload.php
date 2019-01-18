<?php
require_once 'db.php';

class uploadController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function addTrip(){

        if(isset($_POST["Trip"]) && isset($_POST["TripData"]) && isset($_FILES["video"])){
            $data = json_decode($_POST["TripData"]);
            $trip = json_decode($_POST["Trip"]);

            $sql = $this->db->prepare("INSERT INTO Trip (TripID,Trip.Date) VALUES (?,?)");
            $status = $sql->execute(array($trip[0],$trip[1]));
            if (!$status) {
                header("HTTP/1.1 500 Internal Server Error");
                die();
            }

            foreach ($data as $item){
                $sql = $this->db->prepare("INSERT INTO TripData (ID,TripID,TripData.Timestamp,RPM,Speed,Throttle,CTemp,ATemp,Lon,Lat) VALUES (?,?,?,?,?,?,?,?,?,?)");
                $status = $sql->execute(array($item[0],$item[1],$item[2],$item[3],$item[4],$item[5],$item[6],$item[7],$item[8],$item[9]));
                if (!$status) {
                    header("HTTP/1.1 500 Internal Server Error");
                    die();
                }
            }

            if (!move_uploaded_file($_FILES["video"]["tmp_name"], "video/".$_FILES["video"]["name"])) {
                header("HTTP/1.1 500 Internal Server Error");
                die();
            }
        }

    }

}

?>