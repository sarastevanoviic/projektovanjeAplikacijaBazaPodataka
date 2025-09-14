<?php
class Korisnik {
    private mysqli $conn;
    private string $table = "korisnici";

    public function __construct(mysqli $conn){ 
        $this->conn = $conn; 
    }

    // Dohvati korisnika po korisničkom imenu
    public function getKorisnik(string $username): ?array {
        $sql = "SELECT id_korisnika, korisnicko_ime, lozinka 
                FROM {$this->table} 
                WHERE korisnicko_ime = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $rez = $stmt->get_result()->fetch_assoc();
        return $rez ?: null;
    }

    // Kreiraj korisnika
    public function createKorisnik(string $username, string $passwordHash): bool {
        $sql = "INSERT INTO {$this->table} (korisnicko_ime, lozinka) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $passwordHash);
        return $stmt->execute();
    }
}
