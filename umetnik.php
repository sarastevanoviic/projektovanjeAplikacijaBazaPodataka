<?php

class Umetnik implements Crud{

    private $conn;
    private $table = "umetnik";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (ime, prezime, biografija) 
                VALUES (:ime, :prezime, :biografija)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':ime' => $data['ime'],
            ':prezime' => $data['prezime'],
            ':biografija' => $data['biografija']
        ]);
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idumetnika = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table 
                SET ime = :ime, prezime = :prezime, biografija = :biografija 
                WHERE idumetnika = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':ime' => $data['ime'],
            ':prezime' => $data['prezime'],
            ':biografija' => $data['biografija'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idumetnika = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}

?>