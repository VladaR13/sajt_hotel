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
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- NAVIGACIJA -->
<nav class="bg-white fixed w-full z-20 top-0 border-b border-gray-200 shadow">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="index.php" class="flex items-center space-x-3">
      <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png" class="h-8" alt="Logo">
      <span class="text-2xl font-semibold">Hotel rezervacija</span>
    </a>
    <div class="flex space-x-3">
      <?php if(isset($_SESSION['korisnik_id'])): ?>
        <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Odjavi se</a>
      <?php else: ?>
        <a href="login.php" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded">Prijavi se</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container mx-auto mt-28 px-4">

  <!-- Hero sekcija -->
  <div class="flex justify-center items-center mb-12 bg-[url('../img/background_title.png')] h-64 bg-cover bg-center rounded">
    <h1 class="text-3xl md:text-4xl font-bold text-black bg-black/40 p-4 rounded">Dobrodošli na sajt Hotel Rezervacija!</h1>
  </div>

  <!-- Prikaz hotela -->
  <h2 class="text-2xl font-bold mb-6">Naši hoteli</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php
    $hoteli = $conn->query("SELECT * FROM hoteli");
    while($hotel = $hoteli->fetch_assoc()):
    ?>
      <div class="bg-white rounded shadow-md overflow-hidden flex flex-col">
        <img src="../uploads/<?php echo htmlspecialchars($hotel['slika']); ?>" alt="<?php echo htmlspecialchars($hotel['naziv']); ?>" class="h-48 w-full object-cover">
        <div class="p-4 flex-1 flex flex-col justify-between">
          <div>
            <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($hotel['naziv']); ?></h3>
            <p class="text-gray-600 mb-2">Lokacija: <?php echo htmlspecialchars($hotel['lokacija']); ?></p>
            <p class="text-yellow-500 mb-2">Zvezdice: <?php echo $hotel['zvezdice']; ?></p>
            <p class="text-gray-800 font-bold mb-4">Cena: <?php echo $hotel['cena']; ?> RSD / noć</p>
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
      <li><a href="onama/onama.php" class="hover:underline">O nama</a></li>
      <li><a href="kontakt/kontakt.php" class="hover:underline">Kontakt</a></li>
      <li><a href="pravila/pravila.php" class="hover:underline">Pravila</a></li>
    </ul>
    <span class="text-gray-500 text-sm">© 2025 Hotel rezervacija. Sva prava zadržana.</span>
  </div>
</footer>

</body>
</html>
