<?php
require_once __DIR__ . '/crud.php';

class Umetnik implements Crud {
  private $conn; private $table="umetnik";
  public function __construct($conn){ $this->conn=$conn; }

  public function create($data){
    $stmt=$this->conn->prepare("INSERT INTO $this->table (ime,prezime,biografija) VALUES (?,?,?)");
    $stmt->bind_param("sss",$data['ime'],$data['prezime'],$data['biografija']);
    return $stmt->execute();
  }
  public function read($id){
    $stmt=$this->conn->prepare("SELECT * FROM $this->table WHERE idumetnika=?");
    $stmt->bind_param("i",$id); $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
  }
  public function update($id,$data){
    $stmt=$this->conn->prepare("UPDATE $this->table SET ime=?,prezime=?,biografija=? WHERE idumetnika=?");
    $stmt->bind_param("sssi",$data['ime'],$data['prezime'],$data['biografija'],$id);
    return $stmt->execute();
  }
  public function delete($id){
    $stmt=$this->conn->prepare("DELETE FROM $this->table WHERE idumetnika=?");
    $stmt->bind_param("i",$id);
    return $stmt->execute();
  }
}