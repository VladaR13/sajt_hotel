<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "hotel_rezervacije";
// Kreiranje nove konekcije 
$conn = new mysqli($servername, $username, $password, $db);

// Provera konekcije
if ($conn->connect_error) {
  die("Konekcija baze neuspesna! " . $conn->connect_error);
}
echo "<h1 class='text-green-500'>Uspesna konekcija</h1>";
?>