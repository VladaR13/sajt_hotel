<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['uloga']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['hotel_id'])){
    $hotel_id = intval($_GET['hotel_id']);
    $conn->query("DELETE FROM hoteli WHERE hotel_id=$hotel_id");
}

header("Location: hoteli.php");
exit();
