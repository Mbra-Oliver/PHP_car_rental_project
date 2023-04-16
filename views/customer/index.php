<?php 

include('./configs/database.php');

$carsQuery = $databaseConnexion->prepare('SELECT * FROM cars');

print_r($carsQuery);

?>

<h1>Hello</h1>