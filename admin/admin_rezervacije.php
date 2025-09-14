<?php
session_start();
require("../baza.php"); // prilagodi putanju ako je potrebno

// SAMO ADMIN
if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// CSRF token
if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

// --- helper: odredi tabelu rezervacija ako ima 'rezervacije' ili 'rezervacija'
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
    // fallback
    return 'rezervacije';
}
$rez_table = detect_res_table($conn);

// Ako kolona status ne postoji, pokušaj da je dodaš (bezbedno na MySQL 8+)
$res = $conn->query("SHOW COLUMNS FROM `$rez_table` LIKE 'status'");
if ($res && $res->num_rows === 0) {
    $conn->query("ALTER TABLE `$rez_table` ADD COLUMN `status` ENUM('na_cekanju','odobrena','odbijena') NOT NULL DEFAULT 'na_cekanju'");
}

// --- Obrada POST akcija (approve / deny / delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die("Nevažeći CSRF token.");
    }

    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        if ($action === 'approve' || $action === 'deny') {
            $newStatus = $action === 'approve' ? 'odobrena' : 'odbijena';
            $stmt = $conn->prepare("UPDATE `$rez_table` SET status = ? WHERE rezervacija_id = ?");
            $stmt->bind_param("si", $newStatus, $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['flash'] = "Rezervacija #$id je ažurirana na: $newStatus.";
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM `$rez_table` WHERE rezervacija_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['flash'] = "Rezervacija #$id je obrisana.";
        }
    }
    header("Location: admin_rezervacije.php");
    exit;
}

// --- Dohvati rezervacije sa informacijama o korisniku i hotelu
$sql = "SELECT r.*, k.ime AS korisnik_ime, k.email AS korisnik_email, h.naziv AS hotel_naziv
        FROM `$rez_table` r
        LEFT JOIN korisnik k ON r.korisnik_id = k.korisnik_id
        LEFT JOIN hoteli h ON r.hotel_id = h.hotel_id
        ORDER BY r.rezervacija_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="utf-8">
<title>Admin - Rezervacije</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<nav class="bg-white fixed w-full z-20 top-0 border-b p-4">
  <div class="max-w-screen-xl mx-auto flex justify-between items-center">
    <a href="../index.php" class="text-xl font-semibold">Hotel rezervacija - Admin</a>
    <div>
      <a href="../index.php" class="bg-blue-600 text-white px-3 py-2 rounded mr-2">Početna</a>
      <a href="admin_rezervacije.php" class="bg-gray-200 px-3 py-2 rounded">Rezervacije</a>
      <a href="../logout.php" class="bg-red-600 text-white px-3 py-2 rounded ml-2">Odjavi se</a>
    </div>
  </div>
</nav>

<div class="container mx-auto mt-24 px-4">
    <h1 class="text-2xl font-bold mb-4">Sve rezervacije</h1>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($_SESSION['flash']); ?></div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Korisnik</th>
                    <th class="px-4 py-2 text-left">Hotel</th>
                    <th class="px-4 py-2 text-left">Datum od</th>
                    <th class="px-4 py-2 text-left">Datum do</th>
                    <th class="px-4 py-2 text-left">Gostiju</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Akcije</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-t">
                    <td class="px-4 py-3"><?= (int)$row['rezervacija_id'] ?></td>
                    <td class="px-4 py-3">
                        <?= htmlspecialchars($row['korisnik_ime'] ?? $row['korisnik_email'] ?? 'Anonimno') ?>
                        <div class="text-sm text-gray-500"><?= htmlspecialchars($row['korisnik_email'] ?? '') ?></div>
                    </td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['hotel_naziv'] ?? '—') ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['datum_od']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['datum_do']) ?></td>
                    <td class="px-4 py-3"><?= (int)$row['broj_gostiju'] ?></td>
                    <td class="px-4 py-3">
                        <?php
                            $st = $row['status'] ?? 'na_cekanju';
                            $labelClass = $st === 'odobrena' ? 'bg-green-100 text-green-700' : ($st === 'odbijena' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                        ?>
                        <span class="px-2 py-1 rounded <?= $labelClass ?>"><?= htmlspecialchars($st) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <?php if (($row['status'] ?? 'na_cekanju') !== 'odobrena'): ?>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                <input type="hidden" name="id" value="<?= (int)$row['rezervacija_id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Odobri</button>
                            </form>
                            <?php endif; ?>

                            <?php if (($row['status'] ?? 'na_cekanju') !== 'odbijena'): ?>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                <input type="hidden" name="id" value="<?= (int)$row['rezervacija_id'] ?>">
                                <input type="hidden" name="action" value="deny">
                                <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Odbij</button>
                            </form>
                            <?php endif; ?>

                            <form method="post" style="display:inline" onsubmit="return confirm('Obrisati rezervaciju?');">
                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                <input type="hidden" name="id" value="<?= (int)$row['rezervacija_id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Obriši</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="text-center mt-8 p-4 text-sm text-gray-500">© 2025 Hotel rezervacija</footer>
</body>
</html>
