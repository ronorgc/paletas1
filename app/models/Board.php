<?php
class Board {
    private $conn;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para crear un nuevo tablero
    public function create($user_id, $title, $content, $is_public, $is_private) {
        $query = "INSERT INTO boards (user_id, title, content, is_public, is_private) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issii", $user_id, $title, $content, $is_public, $is_private);
        return $stmt->execute(); // Ejecutar y devolver el resultado
    }

    // Función para obtener todos los tableros creados por un usuario
    public function getAllByUser($user_id) {
        $query = "SELECT * FROM boards WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Devolver los resultados como array asociativo
    }

    // Función para encontrar un tablero por su ID
    public function findById($id) {
        $query = "SELECT * FROM boards WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(); // Devolver un solo resultado como array asociativo
    }

    public function update($id, $title, $content, $is_private, $is_public) {
    $query = "UPDATE boards SET title = ?, content = ?, is_private = ?, is_public = ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    
    // Vincular parámetros en el orden correcto
    $stmt->bind_param('ssiii', $title, $content, $is_private, $is_public, $id);
    
    // Ejecutar la consulta y devolver el resultado
    return $stmt->execute();
}

    // Función para eliminar un tablero por su ID
    public function delete($id) {
        $query = "DELETE FROM boards WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute(); // Ejecutar y devolver el resultado
    }

    // Función para obtener todos los tableros públicos
    public function getPublicBoards() {
        $query = "SELECT * FROM boards WHERE is_public = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Devolver los resultados como array asociativo
    }

    // Función para obtener tableros privados de un usuario
    public function getPrivateBoardsByUser($user_id) {
        $query = "SELECT * FROM boards WHERE user_id = ? AND is_private = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

      // Método para incrementar las visitas
    public function incrementViews($id) {
        $stmt = $this->conn->prepare("UPDATE boards SET views = views + 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    public function getAllByUserWithViews($user_id) {
    $query = "SELECT *, views FROM boards WHERE user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

}
?>
