<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

$conn->query("DELETE FROM hotel WHERE hotel_id=$id");

header("Location: pregled_hotela.php");
exit();
?>
