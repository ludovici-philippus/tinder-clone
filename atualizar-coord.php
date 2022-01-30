<?php
    session_start();
    include("MySql.php"); 
    if(!isset($_SESSION['id'])){
        die("");
    }
    $id_usuario = $_SESSION['id'];
    $lat = $_POST['latitude'];
    $long = $_POST['longitude'];
    $sql = MySql::connect()->prepare("UPDATE `usuarios` SET lat_coord = ?, long_coord = ? WHERE id = ?");
    $sql->execute(array($lat, $long, $id_usuario));
    $_SESSION['latitude'] = $lat;
    $_SESSION['longitude'] = $long;
?>