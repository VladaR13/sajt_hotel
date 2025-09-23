<?php
session_start();
require("../baza.php");

if (!isset($_SESSION['uloga']) || $_SESSION['uloga'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$poruka = "";
if (isset($_POST['dodaj_hotel'])) {
    // Preuzimanje podataka o hotelu
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

    // Unos hotela u bazu
    $stmt = $conn->prepare("INSERT INTO hoteli (naziv, lokacija, opis, zvezdice, slika) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Greška u pripremi upita hotela: " . $conn->error);
    }

    $stmt->bind_param("sssis", $naziv, $lokacija, $opis, $zvezdice, $slika);

    if ($stmt->execute()) {
        // Dobijanje ID-a novododatog hotela
        $hotel_id = $stmt->insert_id;

        // Priprema za unos soba
        if (isset($_POST['broj_sobe']) && is_array($_POST['broj_sobe'])) {
            $brojevi_soba = $_POST['broj_sobe'];
            $tipovi_soba = $_POST['tip_sobe'];
            $cene_soba = $_POST['cena'];

            $stmt_sobe = $conn->prepare("INSERT INTO soba (broj_sobe, tip_sobe, cena, hotel_id) VALUES (?, ?, ?, ?)");
            if (!$stmt_sobe) {
                die("Greška u pripremi upita sobe: " . $conn->error);
            }

            for ($i = 0; $i < count($brojevi_soba); $i++) {
                $broj_sobe_temp = intval($brojevi_soba[$i]);
                $tip_sobe_temp = trim($tipovi_soba[$i]);
                $cena_sobe_temp = floatval($cene_soba[$i]);

                $stmt_sobe->bind_param("isdi", $broj_sobe_temp, $tip_sobe_temp, $cena_sobe_temp, $hotel_id);
                if (!$stmt_sobe->execute()) {
                    echo "Greška prilikom dodavanja sobe " . $broj_sobe_temp . ": " . $stmt_sobe->error;
                }
            }
            $stmt_sobe->close();
        }

        $poruka = "Hotel i sobe su uspešno dodati!";
    } else {
        $poruka = "Došlo je do greške prilikom dodavanja hotela: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Dodaj Hotel</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<a href="hoteli.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Nazad</a>

<h2 class="text-2xl font-bold mb-4">Dodaj Novi Hotel</h2>

<?php if($poruka): ?>
<div class="p-3 mb-4 bg-green-200 text-green-800 rounded"><?= $poruka ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md max-w-md space-y-4">
    <input type="text" name="naziv" placeholder="Naziv hotela" required class="w-full border px-3 py-2 rounded">
    <input type="text" name="lokacija" placeholder="Lokacija" required class="w-full border px-3 py-2 rounded">
    <textarea name="opis" placeholder="Opis hotela" required class="w-full border px-3 py-2 rounded"></textarea>
    <input type="number" name="zvezdice" placeholder="Zvezdice" min="1" max="5" required class="w-full border px-3 py-2 rounded">
    <input type="file" name="slika" accept="image/*" required class="w-full border px-3 py-2 rounded">

    <hr class="my-4">
    <h3 class="text-xl font-bold">Dodaj Sobe</h3>
    <div id="sobe-container">
        <div class="soba-input-group space-y-2 border p-3 rounded mb-2">
            <input type="number" name="broj_sobe[]" placeholder="Broj sobe" required class="w-full border px-3 py-2 rounded">
            <input type="text" name="tip_sobe[]" placeholder="Tip sobe (npr. Jednokrevetna, Dvokrevetna)" required class="w-full border px-3 py-2 rounded">
            <input type="number" name="cena[]" placeholder="Cena sobe po noći" min="0" step="0.01" required class="w-full border px-3 py-2 rounded">
        </div>
    </div>
    <button type="button" id="dodaj_sobu" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">➕ Dodaj još jednu sobu</button>
    
    <button type="submit" name="dodaj_hotel" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Dodaj Hotel</button>
</form>

<script>
document.getElementById('dodaj_sobu').addEventListener('click', function() {
    var container = document.getElementById('sobe-container');
    var newDiv = document.createElement('div');
    newDiv.className = 'soba-input-group space-y-2 border p-3 rounded mb-2';
    newDiv.innerHTML = `
        <input type="number" name="broj_sobe[]" placeholder="Broj sobe" required class="w-full border px-3 py-2 rounded">
        <input type="text" name="tip_sobe[]" placeholder="Tip sobe (npr. Jednokrevetna, Dvokrevetna)" required class="w-full border px-3 py-2 rounded">
        <input type="number" name="cena[]" placeholder="Cena sobe po noći" min="0" step="0.01" required class="w-full border px-3 py-2 rounded">
    `;
    container.appendChild(newDiv);
});
</script>

</body>
</html>
