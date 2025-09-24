<?php
session_start();
require("../baza.php"); // konekcija na bazu
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Početna - Hotel rezervacija</title>
<link rel="stylesheet" href="../css/style.css">
<script src="https://cdn.tailwindcss.com" rel="stylesheet"></script>
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
    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'korisnik'): ?>
        <li><a href="moje_rezervacije.php" class="block py-2 px-3 text-white bg-red-600 rounded-sm hover:bg-red-700 md:bg-transparent md:text-red-600 md:p-0 md:dark:text-red-400">Moje rezervacije</a></li>
    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>

<div class="container mx-auto px-4">

  <!-- Hero sekcija -->
  <div class="flex justify-center items-center mb-12 bg-[url('../img/background_title.png')] h-64 bg-cover bg-center rounded">
    <h1 class="text-3xl md:text-4xl font-bold text-white bg-black/40 p-4 rounded">Dobrodošli na sajt Hotel Rezervacija!</h1>
  </div>

  <!-- Prikaz hotela -->
  <h2 class="text-3xl font-bold mb-6">Naši hoteli</h2>
  <hr class="h-px my-8 bg-gray-500 border-0 dark:bg-gray-900">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php
  $sql = "SELECT h.hotel_id, h.naziv, h.lokacija, h.opis, h.zvezdice, h.slika,
  MIN(s.cena) AS min_cena
FROM hoteli h
LEFT JOIN soba s ON h.hotel_id = s.hotel_id
GROUP BY h.hotel_id, h.naziv, h.lokacija, h.opis, h.zvezdice, h.slika
ORDER BY h.naziv ASC";

$result = $conn->query($sql);

while($hotel = $result->fetch_assoc()):
    ?>
      <div class="bg-white rounded shadow-md overflow-hidden flex flex-col">
        <img src="../uploads/<?php echo htmlspecialchars($hotel['slika']); ?>" alt="<?php echo htmlspecialchars($hotel['naziv']); ?>" class="h-48 w-full object-cover">
        <div class="p-4 flex-1 flex flex-col justify-between">
          <div>
            <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($hotel['naziv']); ?></h3>
            <p class="text-gray-600 mb-2">Lokacija: <?php echo htmlspecialchars($hotel['lokacija']); ?></p>
            <p class="text-yellow-500 mb-2">Zvezdice: <?php echo $hotel['zvezdice']; ?></p>
            <p class="text-gray-800 font-bold mb-4">Cena: <?php echo $hotel['min_cena']; ?> RSD / noć</p>
          </div>
          <a href="rezervacija.php?hotel_id=<?php echo $hotel['hotel_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded">Rezerviši</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
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
