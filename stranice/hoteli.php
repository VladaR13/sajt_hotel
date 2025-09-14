<?php
session_start();
require("../baza.php");
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Hoteli</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-10">
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

</body>
</html>
