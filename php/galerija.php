<?php
require_once __DIR__ . '/crud.php';
class Galerija implements Crud {
    private $conn;
    private $table = "galerija";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (naziv_galerije, adresa) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $data['naziv_galerije'], $data['adresa']);
        return $stmt->execute();
    }

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE id_galerije = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table SET naziv_galerije = ?, adresa = ? WHERE id_galerije = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $data['naziv_galerije'], $data['adresa'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id_galerije = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>