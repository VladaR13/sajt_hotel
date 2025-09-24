<?php
session_start();
require("../baza.php");

// Provera da li je korisnik admin
if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Obrada odobrenja ili odbijanja rezervacije
if (isset($_POST['odobri'])) {
    $rez_id = intval($_POST['rezervacija_id']);
    $stmt = $conn->prepare("UPDATE rezervacija SET status='odobrena' WHERE rezervacija_id=?");
    $stmt->bind_param("i", $rez_id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['odbij'])) {
    $rez_id = intval($_POST['rezervacija_id']);
    $stmt = $conn->prepare("UPDATE rezervacija SET status='odbijena' WHERE rezervacija_id=?");
    $stmt->bind_param("i", $rez_id);
    $stmt->execute();
    $stmt->close();
}

// Dohvatanje svih rezervacija
$result = $conn->query("
    SELECT r.rezervacija_id, k.ime, k.prezime, h.naziv AS hotel, r.datum_od, r.datum_do, r.broj_gostiju, r.status
    FROM rezervacija r
    JOIN korisnik k ON r.korisnik_id = k.korisnik_id
    JOIN hoteli h ON r.hotel_id = h.hotel_id
    ORDER BY r.status ASC, r.datum_od ASC
");

if (!$result) {
    die("Greška u SQL upitu: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Odobravanje rezervacija - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<!-- NAVIGACIJA -->
<a href="./odobrenje.php" class="inline-block mb-6 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">&larr; Nazad</a>


<div class="container mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Odobravanje rezervacija</h1>

    <table class="min-w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Korisnik</th>
                <th class="px-4 py-2">Hotel</th>
                <th class="px-4 py-2">Datum od</th>
                <th class="px-4 py-2">Datum do</th>
                <th class="px-4 py-2">Broj gostiju</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Akcija</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr class="<?= $row['status'] === 'na_cekanju' ? 'bg-yellow-100' : ($row['status']==='odobrena' ? 'bg-green-100' : 'bg-red-100') ?>">
                <td class="px-4 py-2"><?= $row['rezervacija_id'] ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['ime'] . ' ' . $row['prezime']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['hotel']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['datum_od']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['datum_do']) ?></td>
                <td class="px-4 py-2"><?= $row['broj_gostiju'] ?></td>
                <td class="px-4 py-2"><?= $row['status'] ?></td>
                <td class="px-4 py-2">
                    <?php if($row['status'] === 'na_cekanju'): ?>
                    <form method="POST" class="flex space-x-2">
                        <input type="hidden" name="rezervacija_id" value="<?= $row['rezervacija_id'] ?>">
                        <button type="submit" name="odobri" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Odobri</button>
                        <button type="submit" name="odbij" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Odbij</button>
                    </form>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
