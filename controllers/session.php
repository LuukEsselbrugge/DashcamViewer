<?php
class sessionController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        session_start();
    }

    public function login(){
        if(isset($_POST["Username"]) && isset($_POST["Password"]) ){
            $sql = $this->db->prepare("SELECT * FROM Users WHERE Username = ?");
            $status = $sql->execute(array($_POST["Username"]));
            if (!$status) {
                echo "Something went wrong. Please try again later";
                die();
            }
            $data = $sql->fetch(PDO::FETCH_ASSOC);

            if($data["Password"] == sha1($data["UserID"].$_POST["Password"])){
                $rdm = substr(str_shuffle(sha1(time())),0,32);

                $sql = $this->db->prepare("INSERT INTO Sessions (UserID,SessionID) VALUES (?,?)");
                $status = $sql->execute(array($data["UserID"],$rdm));
                $_SESSION["SessionID"] = $rdm;
                $_SESSION["Username"] = $data["Username"];
                $_SESSION["UserID"] = $data["UserID"];
                header("Location: " . $_SERVER["HTTP_REFERER"]);
                if (!$status) {
                    echo "Something went wrong. Please try again later";
                    die();
                }
            }else{
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
    }

    public function logout(){
        $sql = $this->db->prepare("DELETE FROM Sessions WHERE SessionID = ? AND UserID = ?");
        $status = $sql->execute(array($_SESSION["SessionID"],$_SESSION["UserID"]));
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
        header("Location: /");
    }

    public function checkSession(){
        $sessionid = "";
        if(isset($_SESSION["SessionID"])){
            $sessionid = $_SESSION["SessionID"];
        }
        $sql = $this->db->prepare("SELECT * FROM Sessions WHERE SessionID = ?");
        $status = $sql->execute(array($sessionid));
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
        $data = $sql->fetch(PDO::FETCH_ASSOC);

        if(isset($data["SessionID"])){
            return true;
        }else{
            return false;
        }
    }

    public function getUser(){
        $sql = $this->db->prepare("SELECT * FROM Users, Sessions WHERE SessionID = ? AND Sessions.UserID = Users.UserID");
        $status = $sql->execute(array($_SESSION["SessionID"]));
        if (!$status) {
            echo "Something went wrong. Please try again later";
            die();
        }
       return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function showLogin(){
        require_once './views/login.php';
    }

}