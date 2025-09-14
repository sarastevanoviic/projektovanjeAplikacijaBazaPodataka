<?php

class Prodaja {
    private $conn;
    private $table = "prodaja";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (idumetnickogdela, datum, kupac, cena, galerija) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issds", 
            $data['idumetnickogdela'], 
            $data['datum'], 
            $data['kupac'], 
            $data['cena'], 
            $data['galerija']
        );
        return $stmt->execute();
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idprodaje = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table 
                SET idumetnickogdela = ?, datum = ?, kupac = ?, cena = ?, galerija = ? 
                WHERE idprodaje = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issdsi", 
            $data['idumetnickogdela'],
            $data['datum'],
            $data['kupac'],
            $data['cena'],
            $data['galerija'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idprodaje = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>