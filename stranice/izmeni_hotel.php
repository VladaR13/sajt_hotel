<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['uloga']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['hotel_id'])){
    die("Hotel nije odabran.");
}

$hotel_id = intval($_GET['hotel_id']);
$hotel = $conn->query("SELECT * FROM hoteli WHERE hotel_id=$hotel_id")->fetch_assoc();
if(!$hotel) die("Hotel nije pronađen.");

$poruka = "";
if(isset($_POST['izmeni_hotel'])){
    $naziv = $_POST['naziv'];
    $lokacija = $_POST['lokacija'];
    $opis = $_POST['opis'];
    $zvezdice = intval($_POST['zvezdice']);
    $cena = floatval($_POST['cena']);

    $slika = $hotel['slika'];
    if(isset($_FILES['slika']) && $_FILES['slika']['error'] == 0){
        $slika = time() . "_" . $_FILES['slika']['name'];
        move_uploaded_file($_FILES['slika']['tmp_name'], "../uploads/" . $slika);
    }

    $stmt = $conn->prepare("UPDATE hoteli SET naziv=?, lokacija=?, opis=?, zvezdice=?, cena=?, slika=? WHERE hotel_id=?");
    $stmt->bind_param("sssidsi", $naziv, $lokacija, $opis, $zvezdice, $cena, $slika, $hotel_id);

    if($stmt->execute()){
        $poruka = "Hotel je uspešno izmenjen!";
        $hotel = $conn->query("SELECT * FROM hoteli WHERE hotel_id=$hotel_id")->fetch_assoc();
    } else {
        $poruka = "Došlo je do greške.";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Izmeni Hotel</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<a href="hoteli.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Nazad</a>

<h2 class="text-2xl font-bold mb-4">Izmeni Hotel</h2>

<?php if($poruka): ?>
<div class="p-3 mb-4 bg-green-200 text-green-800 rounded"><?= $poruka ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md max-w-md space-y-4">
    <input type="text" name="naziv" placeholder="Naziv hotela" value="<?= htmlspecialchars($hotel['naziv']) ?>" required class="w-full border px-3 py-2 rounded">
    <input type="text" name="lokacija" placeholder="Lokacija" value="<?= htmlspecialchars($hotel['lokacija']) ?>" required class="w-full border px-3 py-2 rounded">
    <textarea name="opis" placeholder="Opis hotela" required class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($hotel['opis']) ?></textarea>
    <input type="number" name="zvezdice" placeholder="Zvezdice" min="1" max="5" value="<?= $hotel['zvezdice'] ?>" required class="w-full border px-3 py-2 rounded">
    <input type="number" name="cena" placeholder="Cena po noći" min="0" step="0.01" value="<?= $hotel['cena'] ?>" required class="w-full border px-3 py-2 rounded">
    <input type="file" name="slika" accept="image/*" class="w-full border px-3 py-2 rounded">
    <img src="../uploads/<?= $hotel['slika'] ?>" class="w-32 mt-2">
    <button type="submit" name="izmeni_hotel" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Sačuvaj izmene</button>
</form>

</body>
</html>
