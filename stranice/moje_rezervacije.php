<?php
session_start();
require("../baza.php"); // prilagodi putanju ako treba

if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

// detect table function (isti kao u admin)
function detect_res_table($conn) {
    $candidates = ['rezervacije', 'rezervacija'];
    foreach ($candidates as $t) {
        $sql = "SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $t);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($res && $res['cnt'] > 0) return $t;
    }
    return 'rezervacije';
}
$rez_table = detect_res_table($conn);

// Obrada otkazivanja (DELETE) - dozvoljeno samo za vlastite i samo ako status = 'na_cekanju'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die("Nevažeći CSRF token.");
    }
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM `$rez_table` WHERE rezervacija_id = ? AND korisnik_id = ? AND status = 'na_cekanju'");
        $stmt->bind_param("ii", $id, $_SESSION['korisnik_id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $msg = "Rezervacija je otkazana.";
        } else {
            $msg = "Neuspešno otkazivanje (možda je već odobreno/odbijeno).";
        }
        $stmt->close();
    }
}

// Dohvati rezervacije korisnika
$stmt = $conn->prepare("SELECT r.*, h.naziv AS hotel_naziv FROM `$rez_table` r LEFT JOIN hoteli h ON r.hotel_id = h.hotel_id WHERE r.korisnik_id = ? ORDER BY r.rezervacija_id DESC");
$stmt->bind_param("i", $_SESSION['korisnik_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="utf-8">
<title>Moje rezervacije</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<nav class="bg-white fixed w-full z-20 top-0 border-b p-4">
  <div class="max-w-screen-xl mx-auto flex justify-between items-center">
    <a href="../index.php" class="text-xl font-semibold">Hotel rezervacija</a>
    <div>
      <a href="../index.php" class="bg-blue-600 text-white px-3 py-2 rounded mr-2">Početna</a>
      <a href="../logout.php" class="bg-red-600 text-white px-3 py-2 rounded">Odjavi se</a>
    </div>
  </div>
</nav>

<div class="container mx-auto mt-24 px-4">
    <h1 class="text-2xl font-bold mb-4">Moje rezervacije</h1>

    <?php if (!empty($msg)): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="space-y-4">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="bg-white p-4 rounded shadow">
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold"><?= htmlspecialchars($row['hotel_naziv'] ?? 'Hotel') ?></div>
                    <div class="text-sm text-gray-600">Od: <?= htmlspecialchars($row['datum_od']) ?> — Do: <?= htmlspecialchars($row['datum_do']) ?></div>
                    <div class="text-sm text-gray-600">Gostiju: <?= (int)$row['broj_gostiju'] ?></div>
                </div>
                <div class="text-right">
                    <?php $st = $row['status'] ?? 'na_cekanju'; ?>
                    <div class="mb-2">
                        <span class="<?= $st === 'odobrena' ? 'bg-green-100 text-green-700' : ($st === 'odbijena' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') ?> px-3 py-1 rounded text-sm"><?= htmlspecialchars($st) ?></span>
                    </div>

                    <?php if ($st === 'na_cekanju'): ?>
                        <form method="post" onsubmit="return confirm('Da li želite da otkažete rezervaciju?');">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <input type="hidden" name="id" value="<?= (int)$row['rezervacija_id'] ?>">
                            <button type="submit" name="cancel" class="bg-red-600 text-white px-3 py-1 rounded">Otkazi</button>
                        </form>
                    <?php else: ?>
                        <div class="text-sm text-gray-500">Ne možete otkazati nakon obrade rezervacije.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</div>

<footer class="text-center mt-8 p-4 text-sm text-gray-500">© 2025 Hotel rezervacija</footer>
</body>
</html>
