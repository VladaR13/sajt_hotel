<?php
session_start();
require("../baza.php");

// Provera da li je korisnik admin
if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Obrada odobrenja ili odbijanja rezervacije
if (isset($_POST['odobri'])) {
    $rez_id = intval($_POST['rezervacija_id']);
    $stmt = $conn->prepare("UPDATE rezervacija SET status='odobrena' WHERE rezervacija_id=?");
    $stmt->bind_param("i", $rez_id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['odbij'])) {
    $rez_id = intval($_POST['rezervacija_id']);
    $stmt = $conn->prepare("UPDATE rezervacija SET status='odbijena' WHERE rezervacija_id=?");
    $stmt->bind_param("i", $rez_id);
    $stmt->execute();
    $stmt->close();
}

// Dohvatanje svih rezervacija
$result = $conn->query("
    SELECT r.rezervacija_id, k.ime, k.prezime, h.naziv AS hotel, r.datum_od, r.datum_do, r.broj_gostiju, r.status
    FROM rezervacija r
    JOIN korisnik k ON r.korisnik_id = k.korisnik_id
    JOIN hoteli h ON r.hotel_id = h.hotel_id
    ORDER BY r.status ASC, r.datum_od ASC
");

if (!$result) {
    die("Greška u SQL upitu: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Odobravanje rezervacija - Admin</title>
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
                    <li><a href="../stranice/index.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500">Početna</a></li>
                    <li><a href="../stranice/onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">O nama</a></li>
                    <li><a href="../stranice/hoteli.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Hoteli</a></li>
                    <li><a href="../stranice/kontakt/kontakt.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Kontakt</a></li>
                    <li><a href="../stranice/galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Galerija</a></li>
                    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'): ?>
        <li><a href="./odobrenje.php" class="block py-2 px-3 text-white bg-red-600 rounded-sm hover:bg-red-700 md:bg-transparent md:text-red-600 md:p-0 md:dark:text-red-400">Odobrenje</a></li>
    <?php endif; ?>
    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'korisnik'): ?>
        <li><a href="../stranice/moje_rezervacije.php" class="block py-2 px-3 text-white bg-red-600 rounded-sm hover:bg-red-700 md:bg-transparent md:text-red-600 md:p-0 md:dark:text-red-400">Moje rezervacije</a></li>
    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>
<div class="container mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Odobravanje rezervacija</h1>

    <table class="min-w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Korisnik</th>
                <th class="px-4 py-2">Hotel</th>
                <th class="px-4 py-2">Datum od</th>
                <th class="px-4 py-2">Datum do</th>
                <th class="px-4 py-2">Broj gostiju</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Akcija</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr class="<?= $row['status'] === 'na_cekanju' ? 'bg-yellow-100' : ($row['status']==='odobrena' ? 'bg-green-100' : 'bg-red-100') ?>">
                <td class="px-4 py-2"><?= $row['rezervacija_id'] ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['ime'] . ' ' . $row['prezime']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['hotel']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['datum_od']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['datum_do']) ?></td>
                <td class="px-4 py-2"><?= $row['broj_gostiju'] ?></td>
                <td class="px-4 py-2"><?= $row['status'] ?></td>
                <td class="px-4 py-2">
                    <?php if($row['status'] === 'na_cekanju'): ?>
                    <form method="POST" class="flex space-x-2">
                        <input type="hidden" name="rezervacija_id" value="<?= $row['rezervacija_id'] ?>">
                        <button type="submit" name="odobri" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Odobri</button>
                        <button type="submit" name="odbij" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Odbij</button>
                    </form>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
