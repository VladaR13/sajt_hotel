<?php
session_start();
require("../baza.php");

if(isset($_POST['upload']) && isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'){
    $naziv = $_POST['naziv'];
    $target_dir = "../uploads/galerija/"; // PODFOLDER galerija

    if(!is_dir($target_dir)){
        mkdir($target_dir,0777,true);
    }

    $file_name = time().'_'.basename($_FILES['file']['name']);
    $target_file = $target_dir . $file_name;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg','jpeg','png','gif'];

    if(in_array($fileType, $allowedTypes)){
        if(move_uploaded_file($_FILES['file']['tmp_name'],$target_file)){
            $stmt = $conn->prepare("INSERT INTO galerija (naziv, slika, datum) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss",$naziv,$file_name);
            $stmt->execute();
            $stmt->close();
            header("Location: ../stranice/galerija.php?success=1");
            exit();
        } else {
            echo "Greška pri uploadu slike.";
        }
    } else {
        echo "Dozvoljeni formati: jpg, jpeg, png, gif";
    }
}
?>
