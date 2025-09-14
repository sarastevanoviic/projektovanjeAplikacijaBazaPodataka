<?php

class Korisnik{
    private $conn;
    private $table = "korisnik";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $password) {
        if ($this->getKorisnik($username)) {
            return false; 
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO $this->table (username, password) VALUES (:username, :password)");
        try {
            return $stmt->execute([':username' => $username, ':password' => $hash]);
        } catch (PDOException $e) {
           
            return false;
        }
    }

    public function createKorisnik($username, $password) {
        $stmt = $this->conn->prepare("INSERT INTO korisnici (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $password]);
    }
   
    public function getKorisnik($username) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>