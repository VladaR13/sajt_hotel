<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['uloga']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['hotel_id']) && isset($_GET['soba_id'])){
    $hotel_id = intval($_GET['hotel_id']);
    $soba_id = intval($_GET['soba_id']);
    
    $conn->query("DELETE FROM soba WHERE soba_id=$soba_id AND hotel_id=$hotel_id");
}


header("Location: hoteli.php");
exit();
