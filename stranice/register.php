<?php 
require("../baza.php");
//Konekcija baze 
session_start();


if (isset($_POST['register'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];
    $telefon = $_POST['telefon'];
    $uloga = "korisnik";
    // Sigurnije čuvanje lozinke
    $hashed_password = password_hash($lozinka, PASSWORD_DEFAULT);

    // Provera da li email već postoji
    $check = $conn->prepare("SELECT * FROM korisnik WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Email je već registrovan!";
    } else {
        // Ubacivanje korisnika u bazu
        $stmt = $conn->prepare("INSERT INTO korisnik (ime, prezime, email, lozinka, telefon, uloga) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $ime, $prezime, $email, $hashed_password, $telefon, $uloga);

        if ($stmt->execute()) {
            echo "Uspešno ste se registrovali!";
            // automatsko logovanje posle registracije
            $_SESSION['korisnik_id'] = $stmt->insert_id;
            $_SESSION['ime'] = $ime;
            header("Location: index.php"); // preusmeri na početnu ili panel
            exit();
        } else {
            echo "Greška pri registraciji: " . $stmt->error;
        }
    }
}



?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija - Hotel rezervacija</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
</head>
<body>
    <!-- NAVIGACIJA -->    

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija - Hotel rezervacija</title>
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
  <?php if (isset($_SESSION['korisnik_id'])): ?>
        <a href="./logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Odjavi se</a>
    <?php else: ?>
        <a href="./login.php" class="bg-blue-500 text-white px-4 py-2 rounded">Prijavi se</a>
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
      <li>
        <a href="index.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Početna</a>
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
      <li>
        <a href="galerija.php" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Galerija</a>
    </li>
    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'): ?>
        <li><a href="../admin/odobrenje.php" class="block py-2 px-3 text-white bg-red-600 rounded-sm hover:bg-red-700 md:bg-transparent md:text-red-600 md:p-0 md:dark:text-red-400">Admin panela</a></li>
    <?php endif; ?>
    <?php if (isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'korisnik'): ?>
        <li><a href="moje_rezervacije.php" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500">Moje rezervacije</a></li>
    <?php endif; ?>
    </ul>
  </div>
  </div>
</nav>

<!--Kontejner -->
<div class="container mx-auto">
    <form action="register.php"  method="POST">
      <div class="w-5/6 lg:w-1/2 mx-auto bg-white rounded shadow-xl">
            <div class="py-4 px-8 text-black text-xl border-b border-grey-lighter">Registracija naloga</div>
            <div class="py-4 px-8">
                <div class="flex mb-4">
                    <div class="w-1/2 mr-1">
                        <label class="block text-grey-darker text-sm font-bold mb-2" for="first_name">Ime</label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="first_name" type="text" placeholder="Unesite vase ime" name="ime" required>
                    </div>
                    <div class="w-1/2 ml-1">
                        <label class="block text-grey-darker text-sm font-bold mb-2" for="last_name">Prezime</label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="last_name" type="text" placeholder="Unesite vase prezime" name="prezime" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-grey-darker text-sm font-bold mb-2" for="email">Email adresa</label>
                    <input class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="email" type="email" placeholder="Vasa email adresa" name="email" required>
                </div>
                <div class="mb-4">
                    <label class="block text-grey-darker text-sm font-bold mb-2" for="email">Broj telefona</label>
                    <input class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="telefon" type="tel" placeholder="Vas broj telefona" name="telefon" required>
                </div>
                <div class="mb-4">
                    <label class="block text-grey-darker text-sm font-bold mb-2" for="password">Lozinka</label>
                    <input class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="password" type="password" minlength="8" placeholder="Unesite vasu lozinku" name="lozinka" required>
                    <p class="text-grey text-xs mt-1">Najmanje 8 karaktera</p>
                </div>
                <div class="mb-4">
                    <label class="block text-grey-darker text-sm font-bold mb-2" for="password">Potvrda lozinke</label>
                    <input class="appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="checkpassword" type="password" minlength="8" placeholder="Unesite vasu lozinku" required>
                    <p class="text-grey text-xs mt-1">Najmanje 8 karaktera</p>
                </div>
                <div class="flex items-center justify-between mt-8">
                    <button class="bg-blue-900 hover:bg-blue-dark-400 text-white font-bold py-2 px-4 rounded-full" type="submit" name="register">
                        Registrujte nalog
                    </button>
                </div>
            </div>
        </div>
        </form>
        <p class="text-center my-4">
            <a href="login.php" class="text-blue-700 text-sm no-underline hover:text-grey-darker">Vec imam nalog.</a>
        </p>
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
<script src="../js/navbar.js"></script>
</body>
</html>