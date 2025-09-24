<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['uloga']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['hotel_id'])){
    die("Hotel nije odabran.");
}

$hotel_id = intval($_GET['hotel_id']);
$hotel = $conn->query("SELECT * FROM hoteli WHERE hotel_id=$hotel_id")->fetch_assoc();
if(!$hotel) die("Hotel nije pronađen.");

// Dohvatanje soba
$sobe = $conn->query("SELECT * FROM soba WHERE hotel_id=$hotel_id");
if(!$sobe){
    die("Greška u SQL upitu za sobe: " . $conn->error);
}

$poruka = "";
if(isset($_POST['izmeni_hotel'])){
    $naziv = $_POST['naziv'];
    $lokacija = $_POST['lokacija'];
    $opis = $_POST['opis'];
    $zvezdice = intval($_POST['zvezdice']);

    $slika = $hotel['slika'];
    if(isset($_FILES['slika']) && $_FILES['slika']['error'] == 0){
        $slika = time() . "_" . $_FILES['slika']['name'];
        move_uploaded_file($_FILES['slika']['tmp_name'], "../uploads/" . $slika);
    }

    // Update hotela
    $stmt = $conn->prepare("UPDATE hoteli SET naziv=?, lokacija=?, opis=?, zvezdice=?, slika=? WHERE hotel_id=?");
    $stmt->bind_param("sssisi", $naziv, $lokacija, $opis, $zvezdice, $slika, $hotel_id);

    if($stmt->execute()){
        // Update soba
        if(isset($_POST['soba'])){
            foreach($_POST['soba'] as $soba_id => $soba){
                $broj_sobe = $soba['broj_sobe'];
                $tip_sobe = $soba['tip_sobe'];
                $cena = floatval($soba['cena']);

                $stmt2 = $conn->prepare("UPDATE soba SET broj_sobe=?, tip_sobe=?, cena=? WHERE soba_id=? AND hotel_id=?");
                $stmt2->bind_param("ssdii", $broj_sobe, $tip_sobe, $cena, $soba_id, $hotel_id);
                $stmt2->execute();
            }
        }

        $poruka = "Hotel i sobe su uspešno izmenjeni!";
        $hotel = $conn->query("SELECT * FROM hoteli WHERE hotel_id=$hotel_id")->fetch_assoc();
        $sobe = $conn->query("SELECT * FROM soba WHERE hotel_id=$hotel_id");
    } else {
        $poruka = "Došlo je do greške: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izmena hotela</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-4xl">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Izmeni hotel</h2>

        <?php if ($poruka): ?>
            <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                <?= htmlspecialchars($poruka) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-medium">Naziv</label>
                <input type="text" name="naziv" value="<?= htmlspecialchars($hotel['naziv']) ?>" 
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div>
                <label class="block font-medium">Lokacija</label>
                <input type="text" name="lokacija" value="<?= htmlspecialchars($hotel['lokacija']) ?>" 
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div>
                <label class="block font-medium">Opis</label>
                <textarea name="opis" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200"><?= htmlspecialchars($hotel['opis']) ?></textarea>
            </div>

            <div>
                <label class="block font-medium">Zvezdice</label>
                <input type="number" name="zvezdice" value="<?= $hotel['zvezdice'] ?>" min="1" max="5" 
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="block font-medium">Slika hotela</label>
                <input type="file" name="slika" class="w-full border border-gray-300 rounded px-3 py-2">
                <?php if ($hotel['slika']): ?>
                    <img src="../uploads/<?= $hotel['slika'] ?>" alt="Hotel slika" class="mt-2 h-24 rounded shadow">
                <?php endif; ?>
            </div>

            <h3 class="text-xl font-semibold mt-6 mb-2">Sobe</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 border">Broj sobe</th>
                            <th class="px-4 py-2 border">Tip sobe</th>
                            <th class="px-4 py-2 border">Cena</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($soba = $sobe->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">
                                    <input type="text" name="soba[<?= $soba['soba_id'] ?>][broj_sobe]" 
                                           value="<?= htmlspecialchars($soba['broj_sobe']) ?>" 
                                           class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="text" name="soba[<?= $soba['soba_id'] ?>][tip_sobe]" 
                                           value="<?= htmlspecialchars($soba['tip_sobe']) ?>" 
                                           class="w-full border rounded px-2 py-1">
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="number" step="0.01" name="soba[<?= $soba['soba_id'] ?>][cena]" 
                                           value="<?= $soba['cena'] ?>" 
                                           class="w-full border rounded px-2 py-1">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-6">
                <a href="hoteli.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Nazad</a>
                <button type="submit" name="izmeni_hotel" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Sačuvaj izmene</button>
            </div>
        </form>
    </div>
</body>
</html>
