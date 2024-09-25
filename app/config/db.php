<?php
class DB {
    private $host = "localhost";  // Cambiar si es necesario
    private $db_name = "paleta";  // Asegúrate de que el nombre de la base de datos sea correcto
    private $username = "root";   // Cambiar si es necesario
    private $password = "";       // Cambiar si es necesario
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            if ($this->conn->connect_error) {
                die("Error en la conexión: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conn;
    }
}
