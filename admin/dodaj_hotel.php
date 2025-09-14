<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$poruka = "";
if(isset($_POST['dodaj_hotel'])){
    $naziv = $_POST['naziv'];
    $lokacija = $_POST['lokacija'];
    $opis = $_POST['opis'];

    $stmt = $conn->prepare("INSERT INTO hotel (naziv, lokacija, opis) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $naziv, $lokacija, $opis);

    if($stmt->execute()){
        $poruka = "Hotel je uspešno dodat!";
    } else {
        $poruka = "Došlo je do greške.";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<a href="admin_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Nazad na Dashboard</a>

<h2 class="text-2xl font-bold mb-4">Dodaj Novi Hotel</h2>

<?php if(!empty($poruka)): ?>
<div class="p-3 mb-4 bg-green-200 text-green-800 rounded"><?php echo $poruka; ?></div>
<?php endif; ?>

<form method="post" class="bg-white p-6 rounded shadow-md max-w-md">
    <label class="block mb-2 font-semibold">Naziv Hotela</label>
    <input type="text" name="naziv" required class="w-full mb-4 border rounded px-3 py-2">

    <label class="block mb-2 font-semibold">Lokacija</label>
    <input type="text" name="lokacija" required class="w-full mb-4 border rounded px-3 py-2">

    <label class="block mb-2 font-semibold">Opis</label>
    <textarea name="opis" required class="w-full mb-4 border rounded px-3 py-2"></textarea>

    <button type="submit" name="dodaj_hotel" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Dodaj Hotel</button>
</form>

</body>
</html>
