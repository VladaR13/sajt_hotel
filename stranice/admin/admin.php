<?php
session_start();

// ako nije ulogovan ili nije admin → vrati ga na login
if (!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Hotel rezervacija</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- Navigacija -->
<nav class="bg-blue-800 p-4 text-white flex justify-between">
    <span class="font-bold">Admin Panel</span>
    <a href="../logout.php" class="hover:underline">Odjavi se</a>
</nav>

<!-- Glavni deo -->
<div class="container mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-6">Dobrodošao, <?php echo $_SESSION['ime']; ?>!</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Korisnici -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Upravljanje korisnicima</h2>
            <a href="korisnici.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Pregledaj korisnike</a>
        </div>

        <!-- Hoteli -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Upravljanje hotelima</h2>
            <a href="hoteli.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Dodaj / Uredi hotele</a>
        </div>

        <!-- Statistika -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Statistika</h2>
            <a href="statistika.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Pregledaj statistiku</a>
        </div>
    </div>
</div>

</body>
</html>
