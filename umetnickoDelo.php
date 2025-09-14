<?php

class UmetnickoDelo implements Crud{

  private $conn;
    private $table = "umetnicko_delo";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (naziv, opis, godina, idumetnik, idgalerije) 
                VALUES (:naziv, :opis, :godina, :idumetnik, :idgalerije)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':naziv' => $data['naziv'],
            ':opis' => $data['opis'],
            ':godina' => $data['godina'],
            ':idumetnik' => $data['idumetnik'],
            ':idgalerije' => $data['idgalerije']
        ]);
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idumetnickogdela = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table 
                SET naziv = :naziv, opis = :opis, godina = :godina, idumetnik = :idumetnik, idgalerije = :idgalerije 
                WHERE idumetnickogdela = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':naziv' => $data['naziv'],
            ':opis' => $data['opis'],
            ':godina' => $data['godina'],
            ':idumetnik' => $data['idumetnik'],
            ':idgalerije' => $data['idgalerije'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idumetnickogdela = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}


?>