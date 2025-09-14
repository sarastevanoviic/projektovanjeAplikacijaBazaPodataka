<?php

class Prodaja implements Crud{

  private $conn;
    private $table = "prodaja";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (idumetnickogdela, datum, kupac, cena, galerija) 
                VALUES (:idumetnickogdela, :datum, :kupac, :cena, :galerija)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idumetnickogdela' => $data['idumetnickogdela'],
            ':datum' => $data['datum'],
            ':kupac' => $data['kupac'],
            ':cena' => $data['cena'],
            ':galerija' => $data['galerija']
        ]);
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idprodaje = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table 
                SET idumetnickogdela = :idumetnickogdela, datum = :datum, kupac = :kupac, cena = :cena, galerija = :galerija 
                WHERE idprodaje = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idumetnickogdela' => $data['idumetnickogdela'],
            ':datum' => $data['datum'],
            ':kupac' => $data['kupac'],
            ':cena' => $data['cena'],
            ':galerija' => $data['galerija'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idprodaje = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}


?>