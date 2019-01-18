<?php
require_once 'db.php';

class tripController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();

        if(!isset($_GET["id"])) {
            $ID = $_GET["uri2"];
            require_once './views/trip.php';
        }

    }

    public function getData(){
        $TripID = $_GET["id"];
        $sql = $this->db->prepare("SELECT * FROM TripData, Trip WHERE Trip.TripID = ? AND Trip.TripID=TripData.TripID ORDER BY Timestamp");
        $status = $sql->execute(array($TripID));
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
        header('Content-Type: application/json');
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $json = array();

        foreach ($data as $item){
            $json[strtotime($item["Timestamp"])] = $item;
        }
        echo json_encode($json);

    }

    public function delete(){
        $TripID = stripslashes($_GET["id"]);

        $sql = $this->db->prepare("DELETE FROM Trip WHERE TripID = ?");
        $sql->execute(array($TripID));
        $sql = $this->db->prepare("DELETE FROM TripData WHERE TripID = ?");
        $sql->execute(array($TripID));
        unlink("video/".$TripID.".mp4");

        header("Location: /");
    }


}

?>