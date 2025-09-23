<?php
session_start();
require("../baza.php");

// Logout
if(isset($_POST['logout'])){
    session_destroy();
    header("Location: index.php"); // ili gde hoces da ide korisnik posle logout-a
    exit();
}

// Login logika
$poruka = "";
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];

    $stmt = $conn->prepare("SELECT korisnik_id, ime, lozinka, uloga FROM korisnik WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows == 1){
        $user = $res->fetch_assoc();
        if(password_verify($lozinka, $user['lozinka'])){
            $_SESSION['korisnik_id'] = $user['korisnik_id'];
            $_SESSION['ime'] = $user['ime'];
            $_SESSION['uloga'] = $user['uloga'];
            header("Location: index.php"); // ili gde hoces da ide korisnik posle logout-a
            exit();
            $poruka = "Uspešno ste prijavljeni!";
        } else {
            $poruka = "Pogresna lozinka";
        }
    } else {
        $poruka = "Korisnik ne postoji";
    }
}
?>



<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava - Hotel rezervacija</title>
    <link rel="stylesheet" href="../css/style.css">
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
        <a href="index.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Pocetna</a>
      </li>
      <li>
        <a href="onama/onama.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">O nama</a>
      </li>
      <li>
        <a href="hoteli.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Hoteli</a>
      </li>
      <li>
        <a href="kontakt/kontakt.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Kontakt</a>
      </li>
      <li><a href="galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Galerija</a></li>
    </ul>
  </div>
  </div>
</nav>

<!--Kontejner -->
<div class="container mx-auto">
<!--
  This example requires updating your template:

  ```
  <html class="h-full bg-gray-900">
  <body class="h-full">
  ```
-->
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png?nwm=1&nws=1&industry=hotel&sf=&txt_keyword=All" alt="VR hoteli" class="mx-auto h-10 w-auto" />
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">Prijava</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form action="" method="POST" class="space-y-6" id="loginForm">
      <div>
        <label for="email" class="block text-sm/6 font-medium text-black">Email adresa</label>
        <div class="mt-2">
          <input id="email" type="email" name="email" required autocomplete="email" class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm/6 font-medium text-black">Lozinka</label>
          
        </div>
        <div class="mt-2">
          <input id="password" type="password" name="lozinka" required autocomplete="current-password" class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" />
        </div>
      </div>
      <div class="text-sm">
            <a href="#" data-tooltip-target="my-tooltip" class="font-semibold text-indigo-400 hover:text-indigo-300">Zaboravljena lozinka?</a>
            <div id="my-tooltip" role="tooltip" class="absolute z-10 invisible p-2 text-xs bg-gray-800 text-white rounded-lg shadow-sm">
      Funkcija ne radi...
      <div data-popper-arrow></div>
    </div>
          </div>
      <div>
        <button name="login" type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500" name="login">Prijavite se</button>
      </div>
      <?php if (!empty($poruka)): ?>
    <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        <?php echo $poruka; ?>
    </div>
<?php endif; ?>

    </form>
  </div>
</div>



</div>



<!--FUTER, brza navigacija -->
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

<script src="../../js/login.js"></script>

</body>
</html>