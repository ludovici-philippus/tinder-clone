<?php 
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    define("INCLUDE_PATH", "http://localhost/desenvolvimento-web-tradicional/tinder/");

    $autoload = function($class){
        $class = str_replace("\\", PATH_SEPARATOR, $class);
        include("$class.php");
    };
    spl_autoload_register($autoload);

    !isset($_SESSION['login']) && ($_GET['url'] == "login" || (header("Location: ".INCLUDE_PATH."login") && die()));
    
    $url = isset($_GET['url']) ? explode("/", $_GET['url'])[0] : "home";

    file_exists("pages/$url.php") && include("pages/$url.php");

?>