<?php

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save($username, $password, $email) {
        // Verificar si el email ya existe
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("El email ya está registrado.");
        }

        // Hashear la contraseña antes de guardarla
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertar el nuevo usuario en la base de datos
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if (!$stmt->execute()) {
            throw new Exception("Error al registrar el usuario.");
        }

        return true;
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // Devuelve la fila como un array asociativo
    }
}
