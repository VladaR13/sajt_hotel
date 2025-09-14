<?php
session_start();
require("../baza.php");

// Provera admina
if(!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<nav class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Admin Dashboard</h1>
    <form method="post" action="../logout.php">
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Odjavi se</button>
    </form>
</nav>

<div class="container mx-auto mt-10">
    <h2 class="text-2xl font-semibold mb-6">Dobrodošli, <?php echo $_SESSION['ime']; ?>!</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="dodaj_hotel.php" class="block p-6 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-center">
            <h3 class="text-xl font-bold mb-2">Dodaj Hotel</h3>
            <p>Unesite novi hotel u bazu.</p>
        </a>
        <a href="pregled_hotela.php" class="block p-6 bg-green-500 text-white rounded shadow hover:bg-green-600 text-center">
            <h3 class="text-xl font-bold mb-2">Pregled Hotela</h3>
            <p>Pogledajte i uređujte postojeće hotele.</p>
        </a>
    </div>
</div>

</body>
</html>
