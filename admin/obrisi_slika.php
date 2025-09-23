<?php
session_start();
require("../baza.php");

// Provera admina
if(!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['id'])){
    $id = intval($_POST['id']);

    // Dohvati ime fajla
    $result = $conn->query("SELECT slika FROM galerija WHERE id=$id");
    if($result && $row = $result->fetch_assoc()){
        $file_path = "../uploads/galerija/" . $row['slika'];
        if(file_exists($file_path)){
            unlink($file_path); // obriši fajl
        }
    }

    // Obriši iz baze
    $conn->query("DELETE FROM galerija WHERE id=$id");
}

// Vrati nazad na galeriju
header("Location: ../stranice/galerija.php?deleted=1");
exit();
?>
