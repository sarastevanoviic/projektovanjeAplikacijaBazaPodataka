<?php
// Podaci za konekciju
$servername = "localhost"; // Naziv servera
$username = "root"; // Korisničko ime
$password = ""; // Lozinka
$database = "umetnickadela"; // Naziv baze podataka
// Konekcija sa bazom
$conn = new mysqli($servername, $username, $password, $database);
// Provera konekcije
if ($conn->connect_error) {
    die("Greška prilikom povezivanja sa bazom podataka: " . 
    $conn->connect_error);
}
?>
