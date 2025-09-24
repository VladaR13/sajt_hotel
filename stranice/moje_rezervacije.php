<?php
session_start();
require("../baza.php"); // prilagodi putanju ako treba

if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

// detect table function (isti kao u admin)
function detect_res_table($conn) {
    $candidates = ['rezervacije', 'rezervacija'];
    foreach ($candidates as $t) {
        $sql = "SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $t);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($res && $res['cnt'] > 0) return $t;
    }
    return 'rezervacije';
}
$rez_table = detect_res_table($conn);

// Obrada otkazivanja (DELETE) - dozvoljeno samo za vlastite i samo ako status = 'na_cekanju'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die("Nevažeći CSRF token.");
    }
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM `$rez_table` WHERE rezervacija_id = ? AND korisnik_id = ? AND status = 'na_cekanju'");
        $stmt->bind_param("ii", $id, $_SESSION['korisnik_id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $msg = "Rezervacija je otkazana.";
        } else {
            $msg = "Neuspešno otkazivanje (možda je već odobreno/odbijeno).";
        }
        $stmt->close();
    }
}

// Dohvati rezervacije korisnika
$stmt = $conn->prepare("SELECT r.*, h.naziv AS hotel_naziv FROM `$rez_table` r LEFT JOIN hoteli h ON r.hotel_id = h.hotel_id WHERE r.korisnik_id = ? ORDER BY r.rezervacija_id DESC");
$stmt->bind_param("i", $_SESSION['korisnik_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="utf-8">
<title>Moje rezervacije - Hotel Rezervacija</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
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
                    <li><a href="index.php" aria-current="page" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Početna</a></li>
                    <li><a href="./onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">O nama</a></li>
                    <li><a href="hoteli.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Hoteli</a></li>
                    <li><a href="./kontakt/kontakt.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Kontakt</a></li>
                    <li><a href="galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Galerija</a></li>
                    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'): ?>
        <li><a href="../admin/odobrenje.php" class="block py-2 px-3 text-white bg-red-600 rounded-sm hover:bg-red-700 md:bg-transparent md:text-red-600 md:p-0 md:dark:text-red-400">Odobrenje</a></li>
    <?php endif; ?>
    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'korisnik'): ?>
        <li><a href="moje_rezervacije.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500">Moje rezervacije</a></li>
    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>

<div class="container mx-auto mt-24 px-4">
    <h1 class="text-2xl font-bold mb-4">Moje rezervacije</h1>

    <?php if (!empty($msg)): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="space-y-4">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="bg-white p-4 rounded shadow">
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold"><?= htmlspecialchars($row['hotel_naziv'] ?? 'Hotel') ?></div>
                    <div class="text-sm text-gray-600">Od: <?= htmlspecialchars($row['datum_od']) ?> — Do: <?= htmlspecialchars($row['datum_do']) ?></div>
                    <div class="text-sm text-gray-600">Gostiju: <?= (int)$row['broj_gostiju'] ?></div>
                </div>
                <div class="text-right">
                    <?php $st = $row['status'] ?? 'na_cekanju'; ?>
                    <div class="mb-2">
                        <span class="<?= $st === 'odobrena' ? 'bg-green-100 text-green-700' : ($st === 'odbijena' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') ?> px-3 py-1 rounded text-sm"><?= htmlspecialchars($st) ?></span>
                    </div>

                    <?php if ($st === 'na_cekanju'): ?>
                        <form method="post" onsubmit="return confirm('Da li želite da otkažete rezervaciju?');">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <input type="hidden" name="id" value="<?= (int)$row['rezervacija_id'] ?>">
                            <button type="submit" name="cancel" class="bg-red-600 text-white px-3 py-1 rounded">Otkazi</button>
                        </form>
                    <?php else: ?>
                        <div class="text-sm text-gray-500">Ne možete otkazati nakon obrade rezervacije.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</div>

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
