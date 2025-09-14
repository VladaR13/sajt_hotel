<?php
session_start();
require("../../baza.php"); // konekcija na bazu

$poruka = "";

if (isset($_POST['posalji'])) {
    // Provera da li su polja postavljena
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $subject = isset($_POST['naslov']) ? htmlspecialchars($_POST['naslov']) : '';
    $message = isset($_POST['poruka']) ? htmlspecialchars($_POST['poruka']) : '';

    if($email && $subject && $message){
        $stmt = $conn->prepare("INSERT INTO kontakt (email, naslov, poruka) VALUES (?, ?, ?)");
        if($stmt){
            $stmt->bind_param("sss", $email, $subject, $message);
            if($stmt->execute()){
                $poruka = "Poruka je uspešno poslata!";
            } else {
                $poruka = "Došlo je do greške prilikom slanja poruke.";
            }
            $stmt->close();
        } else {
            $poruka = "Greška u SQL pripremi: " . $conn->error;
        }
    } else {
        $poruka = "Molimo popunite sva polja!";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt - Hotel rezervacija</title>
    <link rel="stylesheet" href="../../css/style.css">
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
        <a href="../logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Odjavi se</a>
      <?php else: ?>
        <a href="../login.php" class="bg-blue-500 text-white px-4 py-2 rounded">Prijavi se</a>
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
        <li><a href="../index.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Pocetna</a></li>
        <li><a href="../onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">O nama</a></li>
        <li><a href="../hoteli.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Hoteli</a></li>
        <li><a href="kontakt.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Kontakt</a></li>
        <li><a href="../galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Galerija</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- SADRZAJ -->
<div class="container mx-auto mt-24 px-4">
    <section class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <h2 class="text-4xl font-bold mb-4 text-center text-blue-700 dark:text-blue-400">Kontaktirajte nas</h2>
        <p class="mb-8 text-center text-gray-500 dark:text-gray-300 sm:text-lg">Imate tehničkih problema? Pošaljite nam upit.</p>
        <form action="#" method="POST" class="space-y-6 max-w-xl mx-auto">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Vaš email</label>
                <input type="email" name="email" id="email" placeholder="adresa@domen.com" class="w-full p-2.5 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" required>
            </div>
            <div>
                <label for="naslov" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Naslov</label>
                <input type="text" name="naslov" id="naslov" placeholder="Kako možemo da Vam pomognemo" class="w-full p-2.5 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" required>
            </div>
            <div>
                <label for="poruka" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Poruka</label>
                <textarea name="poruka" id="poruka" rows="6" placeholder="Ostavite poruku..." class="w-full p-2.5 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" required></textarea>
            </div>
            <button type="submit" name="posalji" class="w-full bg-indigo-500 hover:bg-indigo-400 text-white font-semibold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Pošalji</button>
        </form>
        <?php if (!empty($poruka)): ?>
        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded max-w-xl mx-auto">
            <?php echo $poruka; ?>
        </div>
        <?php endif; ?>
    </section>
</div>

<!-- FUTER -->
<footer class="bg-white dark:bg-gray-900 rounded-lg shadow-md mt-12">
    <div class="max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="../index.php" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png?nwm=1&nws=1&industry=hotel&sf=&txt_keyword=All" class="h-8" alt="Hotel Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Hotel rezervacija</span>
            </a>
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                <li><a href="../onama/onama.php" class="hover:underline me-4 md:me-6">O nama</a></li>
                <li><a href="kontakt.php" class="hover:underline me-4 md:me-6">Kontakt</a></li>
                <li><a href="../pravila/pravila.php" class="hover:underline me-4 md:me-6">Pravila</a></li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 dark:border-gray-700" />
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="../index.php" class="hover:underline">Hotel rezervacija™</a>. Sva prava zadržava vlasnik sajta.</span>
    </div>
</footer>

</body>
</html>
