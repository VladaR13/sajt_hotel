<?php
session_start();
session_destroy();
header("Location: hoteli.php");
exit();
?>