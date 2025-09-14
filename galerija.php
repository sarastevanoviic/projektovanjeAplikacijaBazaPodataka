<?php

class Galerija implements Crud{

   private $conn;
    private $table = "galerija";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (naziv, adresa) VALUES (:naziv, :adresa)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':naziv' => $data['naziv'],
            ':adresa' => $data['adresa']
        ]);
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idgalerije = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table SET naziv = :naziv, adresa = :adresa WHERE idgalerije = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':naziv' => $data['naziv'],
            ':adresa' => $data['adresa'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idgalerije = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}



?>