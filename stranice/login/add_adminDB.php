<?php
require_once("adminCreate.php");

// Kreiranje admina
if (createAdmin("Admin", "admin@domen.com", "admin123")) {
    echo "Admin uspešno kreiran!";
} else {
    echo "Greška prilikom kreiranja admina.";
}
?>
