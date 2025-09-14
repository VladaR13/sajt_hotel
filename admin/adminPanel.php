<?php if(isset($_SESSION['uloga']) && $_SESSION['uloga']=='admin'): ?>
<div class="bg-gray-800 text-white p-2 flex justify-between">
    <span>Zdravo, <?php echo htmlspecialchars($_SESSION['ime']); ?> (Admin)</span>
    <div class="space-x-2">
        <a href="dashboard.php" class="hover:underline">Dashboard</a>
        <form method="post" action="../../logout.php" class="inline">
            <button class="bg-red-600 px-2 py-1 rounded">Odjavi se</button>
        </form>
    </div>
</div>
<?php endif; ?>
