<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

foreach(glob("controllers/*.php") as $file){
    require $file;
}
$ses = new SessionController;

if($ses->checkSession() || isset($_GET["uri"]) && $_GET["uri"] == "session" || isset($_GET["uri"]) && $_GET["uri"] == "upload") {

    if (isset($_GET["uri"])) {
        $cont = $_GET["uri"] . "Controller";
        $cont = new $cont();
    } else {

        $cont = "homeController";
        $cont = new $cont();
    }

    if (isset($_GET["uri2"])) {
        if (method_exists($cont, $_GET["uri2"])) {
            $method = $_GET["uri2"];
            $cont->$method();
        }
    }

}else{
    $ses->showLogin();
}


?>
