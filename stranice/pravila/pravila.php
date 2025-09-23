<?php
session_start();


// Provera da li je korisnik admin
$admin = isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin';
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pravila - Hotel rezervacija</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">

<!-- NAVIGACIJA -->
<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="../index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
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
                    <li><a href="../index.php" aria-current="page" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Pocetna</a></li>
                    <li><a href="../onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">O nama</a></li>
                    <li><a href="../hoteli.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Hoteli</a></li>
                    <li><a href="../kontakt/kontakt.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Kontakt</a></li>
                    <li><a href="../galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Galerija</a></li>
                </ul>
            </div>
        </div>
    </nav>

<!-- SADRZAJ -->
<div class="container mx-auto mt-28 px-4 py-8">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold mb-4 text-center text-blue-700 dark:text-blue-400">Pravila korišćenja sajta</h1>
        <p class="mb-4 text-gray-700 dark:text-gray-300">
            Dobrodošli na naš sajt za rezervaciju hotela. Molimo vas da se pridržavate sledećih pravila kako bi korišćenje sajta bilo sigurno i prijatno za sve korisnike.
        </p>

        <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-900 dark:text-white">1. Registracija i nalog</h2>
        <p class="mb-4 text-gray-700 dark:text-gray-300">
            Svi korisnici moraju da se registruju sa validnom email adresom. Čuvajte svoje pristupne podatke i ne delite ih sa trećim licima.
        </p>

        <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-900 dark:text-white">2. Rezervacija</h2>
        <p class="mb-4 text-gray-700 dark:text-gray-300">
            Rezervacije su obavezujuće. Molimo da redovno proveravate datum i informacije o vašoj rezervaciji. Otkazivanje je moguće u skladu sa pravilima hotela.
        </p>

        <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-900 dark:text-white">3. Ponašanje korisnika</h2>
        <p class="mb-4 text-gray-700 dark:text-gray-300">
            Zabranjeno je postavljanje uvredljivih sadržaja, spamovanje ili pokušaji neovlašćenog pristupa. Kršenje pravila može dovesti do blokiranja naloga.
        </p>

        <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-900 dark:text-white">4. Privatnost i podaci</h2>
        <p class="mb-4 text-gray-700 dark:text-gray-300">
            Vaši lični podaci se čuvaju u skladu sa zakonom o zaštiti podataka. Ne delimo vaše podatke sa trećim stranama bez vaše saglasnosti.
        </p>

        <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-900 dark:text-white">5. Kontakt i podrška</h2>
        <p class="mb-4 text-gray-700 dark:text-gray-300">
            Ako imate pitanja ili probleme, molimo vas da koristite <a href="../kontakt/kontakt.php" class="text-blue-600 hover:underline dark:text-blue-400">kontakt formu</a> za podršku.
        </p>
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
      <li><a href="../onama/onama.php" class="hover:underline">O nama</a></li>
      <li><a href="../kontakt/kontakt.php" class="hover:underline">Kontakt</a></li>
      <li><a href="../galerija.php" class="hover:underline">Galerija</a></li>
      <li><a href="./pravila.php" class="hover:underline">Pravila</a></li>
    </ul>
    <span class="text-gray-500 text-sm">© 2025 Hotel rezervacija. Sva prava zadržana.</span>
  </div>
</footer>

</body>
</html>
