<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM hoteli ORDER BY hotel_id DESC");
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregled Hotela</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<a href="admin_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Nazad na Dashboard</a>

<h2 class="text-2xl font-bold mb-4">Svi Hoteli</h2>

<table class="w-full table-auto bg-white rounded shadow">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Naziv</th>
            <th class="px-4 py-2">Lokacija</th>
            <th class="px-4 py-2">Opis</th>
            <th class="px-4 py-2">Akcija</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="px-4 py-2"><?php echo $row['hotel_id']; ?></td>
            <td class="px-4 py-2"><?php echo $row['naziv']; ?></td>
            <td class="px-4 py-2"><?php echo $row['lokacija']; ?></td>
            <td class="px-4 py-2"><?php echo $row['opis']; ?></td>
            <td class="px-4 py-2 space-x-2">
                <a href="izmeni_hotel.php?id=<?php echo $row['hotel_id']; ?>" class="bg-yellow-400 px-2 py-1 rounded hover:bg-yellow-500 text-white">Izmeni</a>
                <a href="obrisi_hotel.php?id=<?php echo $row['hotel_id']; ?>" class="bg-red-500 px-2 py-1 rounded hover:bg-red-600 text-white" onclick="return confirm('Da li ste sigurni da želite da obrišete hotel?');">Obriši</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
