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
<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 border-b border-gray-200 dark:border-gray-700 shadow">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="../index.php" class="flex items-center space-x-3">
            <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png" class="h-8" alt="Logo">
            <span class="text-2xl font-semibold dark:text-white">Hotel rezervacija</span>
        </a>

        <!-- Dugmad -->
        <div class="flex space-x-3">
            <?php if(isset($_SESSION['korisnik_id'])): ?>
            <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Odjavi se</a>
            <?php else: ?>
            <a href="../login.php" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded">Prijavi se</a>
            <?php endif; ?>
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
            Ako imate pitanja ili probleme, molimo vas da koristite <a href="kontakt/kontakt.php" class="text-blue-600 hover:underline dark:text-blue-400">kontakt formu</a> za podršku.
        </p>

        <p class="mt-6 text-center text-gray-600 dark:text-gray-400">
            © 2025 Hotel rezervacija. Sva prava zadržana.
        </p>
    </div>

</div>

<!-- FUTER -->
<footer class="bg-white dark:bg-gray-900 rounded-lg shadow mt-12 p-6 text-center">
    <span class="text-gray-600 dark:text-gray-400">© 2025 Hotel rezervacija. Sva prava zadržana.</span>
</footer>

</body>
</html>
