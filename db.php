<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host     = "localhost";   // server
$user     = "root";        // korisničko ime
$password = "";            // lozinka 
$database = "umetnickadela"; // ime baze

try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset("utf8mb4"); 
} catch (mysqli_sql_exception $e) {
    die(" Greška pri povezivanju sa bazom: " . $e->getMessage());
}
?>