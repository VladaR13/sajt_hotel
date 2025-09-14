<?php
session_start();
require("../baza.php");

if(!isset($_SESSION['korisnik_id']) || $_SESSION['uloga'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$poruka = "";
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM hotel WHERE hotel_id=$id");
$hotel = $result->fetch_assoc();

if(isset($_POST['izmeni_hotel'])){
    $naziv = $_POST['naziv'];
    $lokacija = $_POST['lokacija'];
    $opis = $_POST['opis'];

    $stmt = $conn->prepare("UPDATE hotel SET naziv=?, lokacija=?, opis=? WHERE hotel_id=?");
    $stmt->bind_param("sssi", $naziv, $lokacija, $opis, $id);

    if($stmt->execute()){
        $poruka = "Hotel je uspešno izmenjen!";
        $hotel['naziv']=$naziv; $hotel['lokacija']=$lokacija; $hotel['opis']=$opis;
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
    <title>Izmeni Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<a href="pregled_hotela.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Nazad na Pregled Hotela</a>

<h2 class="text-2xl font-bold mb-4">Izmeni Hotel</h2>

<?php if(!empty($poruka)): ?>
<div class="p-3 mb-4 bg-green-200 text-green-800 rounded"><?php echo $poruka; ?></div>
<?php endif; ?>

<form method="post" class="bg-white p-6 rounded shadow-md max-w-md">
    <label class="block mb-2 font-semibold">Naziv Hotela</label>
    <input type="text" name="naziv" value="<?php echo $hotel['naziv']; ?>" required class="w-full mb-4 border rounded px-3 py-2">

    <label class="block mb-2 font-semibold">Lokacija</label>
    <input type="text" name="lokacija" value="<?php echo $hotel['lokacija']; ?>" required class="w-full mb-4 border rounded px-3 py-2">

    <label class="block mb-2 font-semibold">Opis</label>
    <textarea name="opis" required class="w-full mb-4 border rounded px-3 py-2"><?php echo $hotel['opis']; ?></textarea>

    <button type="submit" name="izmeni_hotel" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500">Sačuvaj Promene</button>
</form>

</body>
</html>
