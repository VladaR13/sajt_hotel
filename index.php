<?php 
//Konekcija baze 
require("baza.php");





?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pocetna stranica - Hotel rezervacija</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
</head>
<body>
    <!-- NAVIGACIJA -->    
    <nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png?nwm=1&nws=1&industry=hotel&sf=&txt_keyword=All" class="h-8" alt="Hotel Logo">
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Hotel rezervacija</span>
  </a>
  <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
      <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Prijavi se</button>
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
        <a href="index.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Pocetna</a>
      </li>
      <li>
        <a href="/stranice/onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">O nama</a>
      </li>
      <li>
        <a href="/stranice/" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Hoteli</a>
      </li>
      <li>
        <a href="/stranice/kontakt/kontakt.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Kontakt</a>
      </li>
    </ul>
  </div>
  </div>
</nav>

<!--Kontejner -->
<div class="container mx-auto">
    <div class="flex justify-center items-center h-screen bg-[url(img/1.jpg)]" >
        <p class="text-2xl font-bold text-blue-600 hover:text-blue-800">Dobrodošli na sajt hotel rezervacija!</p>
    </div>

    <div class="grid grid-cells"></div>



</div>



<!--FUTER, brza navigacija -->
<footer class="bg-white rounded-lg shadow-sm dark:bg-gray-900 m-4">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="https://flowbite.com/" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Flowbite</span>
            </a>
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                <li>
                    <a href="/stranice/onama/onama.php" class="hover:underline me-4 md:me-6">O nama</a>
                </li>
                <li>
                    <a href="/stranice/kontakt/kontakt.php" class="hover:underline me-4 md:me-6">Kontakt</a>
                </li>
                <li>
                    <a href="/stranice/pravila/pravila.php" class="hover:underline me-4 md:me-6">Pravila</a>
                </li>
                
            </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="index.php" class="hover:underline">Hotel rezervacija™</a>. Sva prava zadrzava vlasnik sajta.</span>
    </div>
</footer>
</body>
</html>