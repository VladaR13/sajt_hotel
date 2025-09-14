<?php
session_start();
require("../baza.php");


if (!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] != 'admin') {
    header("Location: login.php"); // ili gde hoces da preusmeris
    exit();
}






// Provera da li je korisnik ulogovan
if(!isset($_SESSION['korisnik_id'])){
    header("Location: login.php");
    exit();
}

$korisnik_id = $_SESSION['korisnik_id'];

// Dohvatanje svih hotela
$hoteli = $conn->query("SELECT * FROM hoteli ORDER BY naziv ASC");

// Poruka za rezervaciju
$poruka = "";

// Dodavanje rezervacije
if(isset($_POST['rezervisi'])){
    $hotel_id = intval($_POST['hotel_id']);
    $datum_od = $_POST['datum_od'];
    $datum_do = $_POST['datum_do'];
    $broj_gostiju = intval($_POST['broj_gostiju']);

    $stmt = $conn->prepare("INSERT INTO rezervacije (korisnik_id, hotel_id, datum_od, datum_do, broj_gostiju) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $korisnik_id, $hotel_id, $datum_od, $datum_do, $broj_gostiju);

    if($stmt->execute()){
        $poruka = "Rezervacija je uspešno izvršena!";
    } else {
        $poruka = "Došlo je do greške prilikom rezervacije.";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Hoteli</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-10 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6">Lista hotela</h1>

    <?php if(!empty($poruka)): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?= $poruka ?></div>
    <?php endif; ?>
<nav>
  <?php if(isset($_SESSION['uloga'])&& $_SESSION['uloga']=='admin'):?>
    <a href="../admin/admin_dashboard.php" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">Dashboard</a>
    <?php endif;?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php while($h = $hoteli->fetch_assoc()): ?>
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-xl font-semibold"><?= htmlspecialchars($h['naziv']) ?></h2>
                <p class="text-gray-700"><?= htmlspecialchars($h['lokacija']) ?></p>
                <form method="POST" class="mt-3 space-y-2">
                    <input type="hidden" name="hotel_id" value="<?= $h['hotel_id'] ?>" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Datum dolaska</label>
                        <input type="date" name="datum_od" required class="w-full border rounded px-2 py-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Datum odlaska</label>
                        <input type="date" name="datum_do" required class="w-full border rounded px-2 py-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Broj gostiju</label>
                        <input type="number" name="broj_gostiju" min="1" max="10" required class="w-full border rounded px-2 py-1">
                    </div>
                    <button type="submit" name="rezervisi" class="mt-2 w-full bg-blue-600 text-white rounded px-3 py-1 hover:bg-blue-700">Rezerviši</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</nav>
</body>
</html>
