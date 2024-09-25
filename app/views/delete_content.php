<?php
session_start();
require_once '../config/db.php';
require_once '../models/BoardContent.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: /paleta/app/views/login.php?message=Debes iniciar sesión para eliminar contenido.");
    exit();
}

$db = (new DB())->connect();

// Obtener el ID del contenido
if (!isset($_GET['content_id'])) {
    header("Location: /paleta/app/views/view_board.php?message=ID de contenido no especificado.");
    exit();
}

$content_id = $_GET['content_id']; // ID del contenido
$boardContentModel = new BoardContent($db);

// Eliminar el contenido
if ($boardContentModel->delete($content_id)) {
    header("Location: /paleta/app/views/view_board.php?id={$_GET['board_id']}&message=Contenido eliminado exitosamente.");
} else {
    header("Location: /paleta/app/views/view_board.php?id={$_GET['board_id']}&message=Error al eliminar el contenido.");
}
exit();
