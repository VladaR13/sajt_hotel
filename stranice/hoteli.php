<?php
session_start();
require("../baza.php");
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Hoteli - Hotel Rezervacija</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png?nwm=1&nws=1&industry=hotel&sf=&txt_keyword=All" class="h-8" alt="Hotel Logo">
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Hotel rezervacija</span>
  </a>
  <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
  <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
  
  <?php if (isset($_SESSION['korisnik_id'])): ?>
        <!-- Dugme za logout samo kad je korisnik ulogovan -->
        <form method="post" style="display:inline;">
            <button name="logout" type="submit"
                class="text-white ml-4 bg-red-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 text-center">
                Odjavi se
            </button>
        </form>
    <?php else: ?>
        <!-- Link za login kad korisnik NIJE ulogovan -->
        <a href="login.php" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800">
            Prijavi se
        </a>
    <?php endif; ?>
</div>

      <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
        <span class="sr-only">Otvori meni</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
  <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
    <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
      <li>
        <a href="index.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Pocetna</a>
      </li>
      <li>
        <a href="onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">O nama</a>
      </li>
      <li>
        <a href="hoteli.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Hoteli</a>
      </li>
      <li>
        <a href="kontakt/kontakt.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Kontakt</a>
      </li>
      <li><a href="galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Galerija</a></li>
    </ul>
  </div>
  </div>
</nav>
<div class="container mx-auto mt-10 p-2">
    <h1 class="text-2xl font-bold mb-6">Lista hotela</h1>

    <?php if(isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'): ?>
        <a href="dodaj_hotel.php" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Dodaj Hotel</a>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php
        $result = $conn->query("SELECT * FROM hoteli ORDER BY naziv ASC");
        while($hotel = $result->fetch_assoc()):
        ?>
        <div class="bg-white rounded shadow overflow-hidden">
            <img src="../uploads/<?= htmlspecialchars($hotel['slika']) ?>" alt="<?= htmlspecialchars($hotel['naziv']) ?>" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-xl font-semibold"><?= htmlspecialchars($hotel['naziv']) ?></h2>
                <p><?= htmlspecialchars($hotel['lokacija']) ?> | <?= $hotel['zvezdice'] ?> ★ | <?= $hotel['cena'] ?> RSD/noć</p>
                <p class="mt-2"><?= htmlspecialchars($hotel['opis']) ?></p>

                <div class="mt-4 flex space-x-2">
                    <a href="rezervacija.php?hotel_id=<?= $hotel['hotel_id'] ?>" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Rezerviši</a>

                    <?php if(isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'): ?>
                        <a href="izmeni_hotel.php?hotel_id=<?= $hotel['hotel_id'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Izmeni</a>
                        <a href="obrisi_hotel.php?hotel_id=<?= $hotel['hotel_id'] ?>" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700" onclick="return confirm('Da li ste sigurni?')">Obriši</a>
                    <?php endif; ?>
                </div>
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



</body>
</html>
