<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: /paleta/app/views/login.php?message=Por favor, inicie sesión.");
    exit();
}

// Incluir los archivos necesarios
require_once '../config/db.php'; // Asegúrate de incluir la conexión a la base de datos
require_once '../models/Board.php';

// Obtener el ID del tablero de la URL
$board_id = $_GET['id'];

// Crear una instancia de la conexión a la base de datos
$db = (new DB())->connect(); // Conectar a la base de datos

// Crear una instancia del modelo Board con la conexión a la base de datos
$boardModel = new Board($db);

try {
    // Eliminar el tablero
    $boardModel->delete($board_id);
    header("Location: /paleta/app/views/dashboard.php?message=" . urlencode("Tablero eliminado con éxito."));
    exit();
} catch (Exception $e) {
    echo "Error al eliminar el tablero: " . $e->getMessage();
}
?>
