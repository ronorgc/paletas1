<?php
class BoardContent {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para crear contenido
    public function create($board_id, $content_type, $content) {
        $stmt = $this->db->prepare("INSERT INTO board_contents (board_id, content_type, content) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $board_id, $content_type, $content);
        return $stmt->execute();
    }

    // Método para obtener contenido por ID
    public function findById($content_id) {
        $stmt = $this->db->prepare("SELECT * FROM board_contents WHERE id = ?");
        $stmt->bind_param('i', $content_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Método para editar contenido
    public function update($content_id, $content_type, $content) {
        $stmt = $this->db->prepare("UPDATE board_contents SET content_type = ?, content = ? WHERE id = ?");
        $stmt->bind_param('ssi', $content_type, $content, $content_id);
        return $stmt->execute();
    }

    // Método para eliminar contenido
    public function delete($content_id) {
        $stmt = $this->db->prepare("DELETE FROM board_contents WHERE id = ?");
        $stmt->bind_param('i', $content_id);
        return $stmt->execute();
    }

    // Método para obtener todos los contenidos por ID del tablero
    public function getAllByBoardId($board_id) {
        $stmt = $this->db->prepare("SELECT * FROM board_contents WHERE board_id = ?");
        $stmt->bind_param('i', $board_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
