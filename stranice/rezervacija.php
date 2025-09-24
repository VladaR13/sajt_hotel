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
            $stmt->bind_param("iiissi", $_SESSION['korisnik_id'], $soba_id, $hotel_id, $datum_od, $datum_do, $broj_gostiju);
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
<!-- NAVIGACIJA -->
<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="./index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png?nwm=1&nws=1&industry=hotel&sf=&txt_keyword=All" class="h-8" alt="Hotel Logo">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Hotel rezervacija</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <?php if (isset($_SESSION['korisnik_id'])): ?>
                    <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Odjavi se</a>
                <?php else: ?>
                    <a href="login.php" class="bg-blue-500 text-white px-4 py-2 rounded">Prijavi se</a>
                <?php endif; ?>
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Otvori meni</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li><a href="index.php" aria-current="page" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500">Početna</a></li>
                    <li><a href="./onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">O nama</a></li>
                    <li><a href="hoteli.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Hoteli</a></li>
                    <li><a href="./kontakt/kontakt.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Kontakt</a></li>
                    <li><a href="galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Galerija</a></li>
                    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'): ?>
        <li><a href="../admin/odobrenje.php" class="block py-2 px-3 text-white bg-red-600 rounded-sm hover:bg-red-700 md:bg-transparent md:text-red-600 md:p-0 md:dark:text-red-400">Admin panela</a></li>
    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>

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
<!-- FUTER -->
<footer class="bg-white rounded-lg shadow mt-12 p-6 text-center">
  <div class="max-w-screen-xl mx-auto">
    <a href="index.php" class="flex items-center justify-center mb-4 space-x-3">
      <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png" class="h-8" alt="Logo">
      <span class="text-2xl font-semibold">Hotel rezervacija</span>
    </a>
    <ul class="flex justify-center mb-4 text-sm text-gray-500 space-x-6">
      <li><a href="./onama/onama.php" class="hover:underline">O nama</a></li>
      <li><a href="./kontakt/kontakt.php" class="hover:underline">Kontakt</a></li>
      <li><a href="galerija.php" class="hover:underline">Galerija</a></li>
      <li><a href="./pravila/pravila.php" class="hover:underline">Pravila</a></li>
    </ul>
    <span class="text-gray-500 text-sm">© 2025 Hotel rezervacija. Sva prava zadržana.</span>
  </div>
</footer>
<script src="../js/navbar.js"></script>
</body>
</html>
