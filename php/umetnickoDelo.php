<?php
class UmetnickoDelo {
    private $conn;
    private $table = "umetnicko_delo";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // CREATE
    public function create($data) {
        $sql = "INSERT INTO $this->table (naziv, opis, godina, idumetnik, idgalerije) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiii", 
            $data['naziv'], 
            $data['opis'], 
            $data['godina'], 
            $data['idumetnik'], 
            $data['idgalerije']
        );
        return $stmt->execute();
    }

    // READ
    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idumetnickogdela = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // UPDATE
    public function update($id, $data) {
        $sql = "UPDATE $this->table 
                SET naziv = ?, opis = ?, godina = ?, idumetnik = ?, idgalerije = ? 
                WHERE idumetnickogdela = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiiii",
            $data['naziv'],
            $data['opis'],
            $data['godina'],
            $data['idumetnik'],
            $data['idgalerije'],
            $id
        );
        return $stmt->execute();
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idumetnickogdela = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>