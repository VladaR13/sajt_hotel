<?php
session_start();
require("../baza.php");

// Provera da li je korisnik admin
$admin = isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin';
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerija - Hotel rezervacija</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">

<!-- NAVIGACIJA -->
<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 border-b border-gray-200 dark:border-gray-700 shadow">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="index.php" class="flex items-center space-x-3">
            <img src="https://www.logodesign.net/logo/line-art-buildings-in-swoosh-1273ld.png" class="h-8" alt="Logo">
            <span class="text-2xl font-semibold dark:text-white">Hotel rezervacija</span>
        </a>
        
        <div class="flex space-x-3">
            <?php if(isset($_SESSION['korisnik_id'])): ?>
                <form method="post" action="logout.php">
                    <button type="submit" name="logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Odjavi se</button>
                </form>
            <?php else: ?>
                <a href="login.php" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded">Prijavi se</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- SADRZAJ -->
<div class="container mx-auto mt-28 px-4">

    <?php if($admin): ?>
        <!-- ADMIN PANEL: Upload slike -->
        <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4 text-blue-700 dark:text-blue-400">Dodaj novu sliku</h2>
            <form action="upload_slika.php" method="post" enctype="multipart/form-data" class="space-y-3">
                <input type="text" name="naziv" placeholder="Naziv slike" class="border rounded w-full py-2 px-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                <input type="file" name="file" class="border rounded w-full py-2 px-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                <button type="submit" name="upload" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded">Postavi sliku</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- GALERIJA SLIKA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        $result = $conn->query("SELECT * FROM galerija ORDER BY datum DESC");
        while($row = $result->fetch_assoc()):
        ?>
            <div class="bg-white dark:bg-gray-800 border rounded-lg overflow-hidden shadow-md">
                <img src="../../uploads/<?php echo htmlspecialchars($row['slika']); ?>" alt="<?php echo htmlspecialchars($row['naziv']); ?>" class="w-full h-48 object-cover">
                <div class="p-3 text-center font-medium dark:text-white"><?php echo htmlspecialchars($row['naziv']); ?></div>
                <?php if($admin): ?>
                    <form action="obrisi_slika.php" method="post" class="text-center mb-2">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">Obriši</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

</div>

<!-- FUTER -->
<footer class="bg-white dark:bg-gray-900 rounded-lg shadow mt-12 p-6 text-center">
    <span class="text-gray-600 dark:text-gray-400">© 2025 Hotel rezervacija. Sva prava zadržana.</span>
</footer>

</body>
</html>
