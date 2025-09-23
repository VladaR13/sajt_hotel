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
    die("Hotel nije odabran.");
}

$hotel_id = intval($_GET['hotel_id']);

// Dohvatanje podataka hotela
$stmt = $conn->prepare("SELECT naziv, lokacija, zvezdice FROM hoteli WHERE hotel_id=?");
if (!$stmt) die("Greška u SQL upitu: " . $conn->error);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$hotel = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$hotel) die("Hotel nije pronađen.");

// Dohvatanje soba za odabrani hotel
$stmt = $conn->prepare("SELECT soba_id, tip_sobe, broj_sobe, cena FROM soba WHERE hotel_id=?");
if (!$stmt) die("Greška u SQL upitu: " . $conn->error);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$sobe_result = $stmt->get_result();
$sobe = [];
while($row = $sobe_result->fetch_assoc()) {
    $sobe[] = $row;
}
$stmt->close();

$poruka = "";

// Obrada forme rezervacije
if (isset($_POST['rezervisi'])) {
    $soba_id = intval($_POST['soba_id']);
    $datum_od = $_POST['datum_od'];
    $datum_do = $_POST['datum_do'];
    $broj_gostiju = intval($_POST['broj_gostiju']);

    // Validacija datuma
    if ($datum_od >= $datum_do) {
        $poruka = "❌ Datum od mora biti pre datuma do.";
    } else {
        // Provera da li soba postoji u hotelu
        $stmt = $conn->prepare("SELECT soba_id FROM soba WHERE soba_id=? AND hotel_id=?");
        $stmt->bind_param("ii", $soba_id, $hotel_id);
        $stmt->execute();
        $soba_check = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$soba_check) {
            $poruka = "❌ Odabrana soba ne postoji u ovom hotelu.";
        } else {
            // Ubacivanje rezervacije
            $stmt = $conn->prepare("INSERT INTO rezervacija (korisnik_id, soba_id, hotel_id, datum_od, datum_do, broj_gostiju, status) VALUES (?,?,?,?,?,?,'na_cekanju')");
            $status = 'na_cekanju';
            $stmt->bind_param("iiiisi", $_SESSION['korisnik_id'], $soba_id, $hotel_id, $datum_od, $datum_do, $broj_gostiju);
            if ($stmt->execute()) {
                $poruka = "✅ Rezervacija je uspešno kreirana! Čeka odobrenje administratora.";
            } else {
                $poruka = "❌ Došlo je do greške: " . $stmt->error;
            }
            $stmt->close();
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
    <p><strong>Zvezdice:</strong> <?= htmlspecialchars($hotel['zvezdice']) ?></p>

    <?php if (!empty($poruka)): ?>
        <div class="mt-4 p-3 <?= strpos($poruka, '✅') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> rounded">
            <?= $poruka ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-6 space-y-4">
        <div>
            <label for="soba_id" class="block mb-1 font-medium">Odaberite sobu:</label>
            <select id="soba_id" name="soba_id" required class="w-full border px-3 py-2 rounded">
                <option value="">-- Izaberite sobu --</option>
                <?php foreach($sobe as $s): ?>
                    <option value="<?= $s['soba_id'] ?>">
                        <?= htmlspecialchars($hotel['naziv']) ?> - Broj sobe: <?=htmlspecialchars($s["broj_sobe"]) ?> - Tip sobe: <?=htmlspecialchars($s["tip_sobe"]) ?> - Cena: <?= number_format($s['cena'],0,",",".") ?> RSD
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
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
