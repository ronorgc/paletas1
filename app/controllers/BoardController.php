<?php
require_once '../config/db.php';
require_once '../models/Board.php';

class BoardController {
    public function create() {
        if ($_POST) {
            $board = new Board();
            $board->title = $_POST['title'];
            $board->content = $_POST['content'];
            $board->is_private = isset($_POST['is_private']);
            $board->invitation_code = $_POST['invitation_code'] ?? null;
            $board->user_id = $_SESSION['user_id']; // Asegúrate de iniciar sesión

            $result = $board->save();
            header("Location: ../views/dashboard.php");
        }
    }

    public function delete($id) {
        $board = new Board();
        $board->delete($id);
        header("Location: ../views/dashboard.php");
    }

    public function view($id) {
        $board = new Board();
        return $board->find($id);
    }

    public function listPublicBoards() {
        $board = new Board();
        return $board->getPublicBoards();
    }


}

class BoardContent {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($board_id, $content_type, $content) {
        $query = "INSERT INTO board_contents (board_id, content_type, content) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $board_id, $content_type, $content);
        return $stmt->execute();
    }

    public function getByBoardId($board_id) {
        $query = "SELECT * FROM board_contents WHERE board_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $board_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}



class BoardContent {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($board_id, $content_type, $content) {
        $query = "INSERT INTO board_contents (board_id, content_type, content) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $board_id, $content_type, $content);
        return $stmt->execute();
    }

    public function getByBoardId($board_id) {
        $query = "SELECT * FROM board_contents WHERE board_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $board_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

?>
