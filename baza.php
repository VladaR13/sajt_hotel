<?php
$servername = "localhost";
$username = "root";
$password = "";

// Kreiranje nove konekcije 
$conn = new mysqli($servername, $username, $password);

// Provera konekcije
if ($conn->connect_error) {
  die("Konekcija baze neuspesna! " . $conn->connect_error);
}
echo "<h1 class='text-green-500'>Uspesna konekcija</h1>";
?>