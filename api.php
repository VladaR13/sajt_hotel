<?php
require("../../baza.php");
header('Content-Type: application/json'); //ovaj kod nigde nisam koristio, testirao sam iz radoznalosti...

$res = $conn->query("SELECT * FROM hoteli");
$data = [];
while($row = $res->fetch_assoc()){
    $data[] = $row;
}
echo json_encode($data);