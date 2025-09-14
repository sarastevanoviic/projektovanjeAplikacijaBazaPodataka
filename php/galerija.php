<?php

class Galerija {
    private $conn;
    private $table = "galerija";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (naziv, adresa) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $data['naziv'], $data['adresa']);
        return $stmt->execute();
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idgalerije = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table SET naziv = ?, adresa = ? WHERE idgalerije = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $data['naziv'], $data['adresa'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idgalerije = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>