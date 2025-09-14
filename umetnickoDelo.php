<?php

class UmetnickoDelo {
    private $conn;
    private $table = "umetnicko_delo";

    public function __construct($conn) {
        $this->conn = $conn;
    }

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

    public function read($id) {
        $sql = "SELECT * FROM $this->table WHERE idumetnickogdela = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table 
                SET naziv = ?, opis = ?, godina = ?, idumetnik = ?, idgalerije = ? 
                WHERE idumetnickogdela = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiii i",
            $data['naziv'],
            $data['opis'],
            $data['godina'],
            $data['idumetnik'],
            $data['idgalerije'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idumetnickogdela = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>