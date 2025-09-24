<?php
session_start();
require("../baza.php");

// Provera da li je korisnik admin
if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Brisanje korisnika
if (isset($_POST['obrisi'])) {
    $korisnik_id = intval($_POST['korisnik_id']);

    // 1. Obriši sve rezervacije tog korisnika
    $stmt1 = $conn->prepare("DELETE FROM rezervacija WHERE korisnik_id=?");
    $stmt1->bind_param("i", $korisnik_id);
    $stmt1->execute();
    $stmt1->close();

    // 2. Obriši korisnika
    $stmt2 = $conn->prepare("DELETE FROM korisnik WHERE korisnik_id=? AND uloga!='admin'");
    $stmt2->bind_param("i", $korisnik_id);
    if ($stmt2->execute()) {
        $poruka = "Korisnik i njegove rezervacije su uspešno obrisani!";
    } else {
        $poruka = "Greška prilikom brisanja korisnika: " . $stmt2->error;
    }
    $stmt2->close();
}


// Dohvatanje liste korisnika osim admina
$result = $conn->query("SELECT korisnik_id, ime, prezime, email, uloga FROM korisnik WHERE uloga!='admin' ORDER BY korisnik_id ASC");
if (!$result) {
    die("Greška u SQL upitu: " . $conn->error);
}




?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Lista korisnika - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="container mx-auto">
    <a href="../stranice/index.php" class="inline-block mb-6 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">&larr; Nazad</a>
    <a href="lista_rezervacije.php" class="inline-block mb-6 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"> Pogledaj rezervacije</a>
    <h1 class="text-2xl font-bold mb-4">Lista korisnika</h1>

    <?php if(isset($poruka)): ?>
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded"><?= $poruka ?></div>
    <?php endif; ?>

    <table class="min-w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Ime</th>
                <th class="px-4 py-2">Prezime</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Uloga</th>
                <th class="px-4 py-2">Akcija</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr class="border-b hover:bg-gray-100">
                <td class="px-4 py-2"><?= $row['korisnik_id'] ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['ime']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['prezime']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['uloga']) ?></td>
                <td class="px-4 py-2">
                    <form method="POST">
                        <input type="hidden" name="korisnik_id" value="<?= $row['korisnik_id'] ?>">
                        <button type="submit" name="obrisi" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700"
                        onclick="return confirm('Da li ste sigurni da želite da obrišete ovog korisnika?')">Obriši</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
