<?php

class Baza {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "umetnickadela";
    public $conn;

       public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              echo "Konekcija uspešna!";
        } catch (PDOException $e) {
            die("Konekcija neuspešna: " . $e->getMessage());
        }
    }

    // Metod za pristup konekciji
    public function getConnection() {
        return $this->conn;
    }
}
?>

