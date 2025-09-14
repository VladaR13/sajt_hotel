<?php
session_start();
require("../baza.php");

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit();
}

// Dohvatanje ID hotela iz GET parametra
if (!isset($_GET['hotel_id'])) {
    echo "Hotel nije odabran.";
    exit();
}

$hotel_id = intval($_GET['hotel_id']);

// Dohvatanje podataka hotela
$stmt = $conn->prepare("SELECT naziv, lokacija, zvezdice, cena FROM hoteli WHERE hotel_id=?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$hotel = $stmt->get_result()->fetch_assoc();

if (!$hotel) {
    echo "Hotel nije pronađen.";
    exit();
}

$poruka = "";

// Obrada forme rezervacije
if (isset($_POST['rezervisi'])) {
    $datum_od = $_POST['datum_od'];
    $datum_do = $_POST['datum_do'];
    $broj_gostiju = intval($_POST['broj_gostiju']);

    // Validacija datuma
    if ($datum_od >= $datum_do) {
        $poruka = "❌ Datum od mora biti pre datuma do.";
    } else {
        $stmt = $conn->prepare("INSERT INTO rezervacije (korisnik_id, hotel_id, datum_od, datum_do, broj_gostiju, status) VALUES (?,?,?,?,?, 'na_cekanju')");
        $stmt->bind_param("iissi", $_SESSION['korisnik_id'], $hotel_id, $datum_od, $datum_do, $broj_gostiju);

        if ($stmt->execute()) {
            $poruka = "✅ Rezervacija je uspešno kreirana! Čeka odobrenje administratora.";
        } else {
            $poruka = "❌ Došlo je do greške: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Rezervacija - <?= htmlspecialchars($hotel['naziv']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-10 max-w-lg bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4"><?= htmlspecialchars($hotel['naziv']) ?></h1>
    <p><strong>Lokacija:</strong> <?= htmlspecialchars($hotel['lokacija']) ?></p>
    <p><strong>Zvezdice:</strong> <?= $hotel['zvezdice'] ?></p>
    <p><strong>Cena po noći:</strong> <?= number_format($hotel['cena'], 0, ",", ".") ?> RSD</p>

    <?php if (!empty($poruka)): ?>
        <div class="mt-4 p-3 <?= strpos($poruka, '✅') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> rounded">
            <?= $poruka ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-6 space-y-4">
        <div>
            <label for="datum_od" class="block mb-1 font-medium">Datum od:</label>
            <input type="date" id="datum_od" name="datum_od" required class="w-full border px-3 py-2 rounded">
        </div>
        <div>
            <label for="datum_do" class="block mb-1 font-medium">Datum do:</label>
            <input type="date" id="datum_do" name="datum_do" required class="w-full border px-3 py-2 rounded">
        </div>
        <div>
            <label for="broj_gostiju" class="block mb-1 font-medium">Broj gostiju:</label>
            <input type="number" id="broj_gostiju" name="broj_gostiju" min="1" value="1" required class="w-full border px-3 py-2 rounded">
        </div>
        <button type="submit" name="rezervisi" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Rezerviši</button>
    </form>
</div>

</body>
</html>
