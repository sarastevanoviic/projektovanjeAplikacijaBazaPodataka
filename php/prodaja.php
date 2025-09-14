<?php

class Prodaja {
    private $conn;
    private $table = "prodaja";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (umetnicko_delo_id, datum, kupac, cena, galerija_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issds", 
            $data['umetnicko_delo_id'], 
            $data['datum'], 
            $data['kupac'], 
            $data['cena'], 
            $data['galerija_id']
        );
        return $stmt->execute();
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE id_prodaje = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table 
                SET umetnicko_delo_id = ?, datum = ?, kupac = ?, cena = ?, galerija_id = ? 
                WHERE id_prodaje = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issdsi", 
            $data['umetnicko_delo_id'],
            $data['datum'],
            $data['kupac'],
            $data['cena'],
            $data['galerija_id'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id_prodaje = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>