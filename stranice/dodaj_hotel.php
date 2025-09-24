<?php
session_start();
require("../baza.php");

if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$poruka = "";
if (isset($_POST['dodaj_hotel'])) {
    $naziv = trim($_POST['naziv']);
    $lokacija = trim($_POST['lokacija']);
    $opis = trim($_POST['opis']);
    $zvezdice = intval($_POST['zvezdice']);

    // Upload slike
    $slika = "";
    if (isset($_FILES['slika']) && $_FILES['slika']['error'] == 0) {
        $slika = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $_FILES['slika']['name']);
        move_uploaded_file($_FILES['slika']['tmp_name'], "../uploads/" . $slika);
    }

    // Unos hotela
    $stmt = $conn->prepare("INSERT INTO hoteli (naziv, lokacija, opis, zvezdice, slika) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $naziv, $lokacija, $opis, $zvezdice, $slika);

    if ($stmt->execute()) {
        $hotel_id = $stmt->insert_id;

        if (isset($_POST['broj_sobe'])) {
            $stmt_sobe = $conn->prepare("INSERT INTO soba (broj_sobe, tip_sobe, cena, hotel_id) VALUES (?, ?, ?, ?)");
            foreach ($_POST['broj_sobe'] as $i => $broj) {
                $broj_sobe = intval($_POST['broj_sobe'][$i]);
                $tip_sobe = trim($_POST['tip_sobe'][$i]);
                $cena = floatval($_POST['cena'][$i]);

                $stmt_sobe->bind_param("isdi", $broj_sobe, $tip_sobe, $cena, $hotel_id);
                $stmt_sobe->execute();
            }
        }

        $poruka = "Hotel i sobe su uspešno dodati!";
    } else {
        $poruka = "Greška pri dodavanju hotela: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Dodaj Hotel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<a href="hoteli.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Nazad</a>

<h2 class="text-2xl font-bold mb-4">Dodaj Novi Hotel</h2>

<?php if($poruka): ?>
<div class="p-3 mb-4 bg-green-200 text-green-800 rounded"><?= $poruka ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md space-y-4">
    <input type="text" name="naziv" placeholder="Naziv hotela" required class="w-full border px-3 py-2 rounded">
    <input type="text" name="lokacija" placeholder="Lokacija" required class="w-full border px-3 py-2 rounded">
    <textarea name="opis" placeholder="Opis hotela" required class="w-full border px-3 py-2 rounded"></textarea>
    <input type="number" name="zvezdice" placeholder="Zvezdice (1-5)" min="1" max="5" required class="w-full border px-3 py-2 rounded">
    <input type="file" name="slika" accept="image/*" required class="w-full border px-3 py-2 rounded">

    <h3 class="text-xl font-bold mt-6 mb-2">Dodaj sobe</h3>
    <table class="w-full border mb-4" id="tabela_soba">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-2 py-1">Broj sobe</th>
                <th class="border px-2 py-1">Tip sobe</th>
                <th class="border px-2 py-1">Cena</th>
                <th class="border px-2 py-1">Akcija</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-2 py-1"><input type="number" name="broj_sobe[]" class="w-full border px-2 py-1 rounded" required></td>
                <td class="border px-2 py-1"><input type="text" name="tip_sobe[]" class="w-full border px-2 py-1 rounded" required></td>
                <td class="border px-2 py-1"><input type="number" name="cena[]" step="0.01" class="w-full border px-2 py-1 rounded" required></td>
                <td class="border px-2 py-1 text-center"><button type="button" onclick="obrisiRed(this)" class="text-red-500">✖</button></td>
            </tr>
        </tbody>
    </table>
    <button type="button" onclick="dodajSobu()" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">➕ Dodaj sobu</button>

    <button type="submit" name="dodaj_hotel" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Dodaj Hotel</button>
</form>

<script>
function dodajSobu() {
    let tabela = document.getElementById("tabela_soba").querySelector("tbody");
    let red = document.createElement("tr");
    red.innerHTML = `
        <td class="border px-2 py-1"><input type="number" name="broj_sobe[]" class="w-full border px-2 py-1 rounded" required></td>
        <td class="border px-2 py-1"><input type="text" name="tip_sobe[]" class="w-full border px-2 py-1 rounded" required></td>
        <td class="border px-2 py-1"><input type="number" name="cena[]" step="0.01" class="w-full border px-2 py-1 rounded" required></td>
        <td class="border px-2 py-1 text-center"><button type="button" onclick="obrisiRed(this)" class="text-red-500">✖</button></td>
    `;
    tabela.appendChild(red);
}

function obrisiRed(btn) {
    btn.closest("tr").remove();
}
</script>

</body>
</html>
