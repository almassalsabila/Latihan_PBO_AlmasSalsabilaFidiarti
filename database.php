<?php
class Database {
    private $host = "localhost";
    private $username = "root"; 
    private $password = "";     
    private $db_name = "db_latihan_pbo_trpl1a_almas";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Koneksi ke database gagal: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>