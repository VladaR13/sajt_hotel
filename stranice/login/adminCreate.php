<?php
// adminCreate.php
require_once("../../baza.php"); // konekcija na bazu

function createAdmin($ime, $email, $lozinka) {
    global $conn;


    

    $stmt = $conn->prepare(
        "INSERT INTO korisnik (ime, prezime, email, lozinka, telefon, uloga) VALUES (?, ?, ?, ?, ?, ?)"
    );
    
    $ime = "Admin";
    $prezime = "Admin";
    $email = "admin@domen.com";
    $lozinka = password_hash("admin123", PASSWORD_DEFAULT); // obavezno hashovanje!
    $telefon = "000000000";
    $uloga = "admin";
    
    $stmt->bind_param("ssssss", $ime, $prezime, $email, $lozinka, $telefon, $uloga);
    $stmt->execute();
    
}
?>
