<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['uloga']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$poruka = "";
if(isset($_POST['dodaj_hotel'])){
    $naziv = $_POST['naziv'];
    $lokacija = $_POST['lokacija'];
    $opis = $_POST['opis'];
    $zvezdice = intval($_POST['zvezdice']);
    $cena = floatval($_POST['cena']);

    // Upload slike
    $slika = "";
    if(isset($_FILES['slika']) && $_FILES['slika']['error'] == 0){
        $slika = time() . "_" . $_FILES['slika']['name'];
        move_uploaded_file($_FILES['slika']['tmp_name'], "../uploads/" . $slika);
    }

    $stmt = $conn->prepare("INSERT INTO hoteli (naziv, lokacija, opis, zvezdice, cena, slika) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssids", $naziv, $lokacija, $opis, $zvezdice, $cena, $slika);

    if($stmt->execute()){
        $poruka = "Hotel je uspešno dodat!";
    } else {
        $poruka = "Došlo je do greške.";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Dodaj Hotel</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<a href="hoteli.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Nazad</a>

<h2 class="text-2xl font-bold mb-4">Dodaj Novi Hotel</h2>

<?php if($poruka): ?>
<div class="p-3 mb-4 bg-green-200 text-green-800 rounded"><?= $poruka ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md max-w-md space-y-4">
    <input type="text" name="naziv" placeholder="Naziv hotela" required class="w-full border px-3 py-2 rounded">
    <input type="text" name="lokacija" placeholder="Lokacija" required class="w-full border px-3 py-2 rounded">
    <textarea name="opis" placeholder="Opis hotela" required class="w-full border px-3 py-2 rounded"></textarea>
    <input type="number" name="zvezdice" placeholder="Zvezdice" min="1" max="5" required class="w-full border px-3 py-2 rounded">
    <input type="number" name="cena" placeholder="Cena po noći" min="0" step="0.01" required class="w-full border px-3 py-2 rounded">
    <input type="file" name="slika" accept="image/*" required class="w-full border px-3 py-2 rounded">
    <button type="submit" name="dodaj_hotel" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Dodaj Hotel</button>
</form>

</body>
</html>
