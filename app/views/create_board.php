<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /paleta/app/views/login.php?message=Por favor, inicie sesión.");
    exit();
}

require_once '../config/db.php';
require_once '../models/Board.php';

$db = (new DB())->connect(); // Conexión a la base de datos
$boardModel = new Board($db);
$user_id = $_SESSION['user_id']; // ID del usuario logueado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Revisamos y asignamos la visibilidad basada en el valor de 'visibility'
    $visibility = $_POST['visibility'];
    if ($visibility === 'private') {
        $is_private = 1;
        $is_public = 0;
    } elseif ($visibility === 'public') {
        $is_private = 0;
        $is_public = 1;
    } else {
        $is_private = 0;
        $is_public = 0;
    }

    // Intentamos crear el tablero en la base de datos
    if ($boardModel->create($user_id, $title, $content, $is_public, $is_private)) {
        header("Location: dashboard.php?message=Tablero creado exitosamente.");
        exit();
    } else {
        echo "Error al crear el tablero.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tablero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .form-header h1 {
            margin: 0;
        }
        .form-header .btn-logout {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }
        .form-header .btn-logout:hover {
            background-color: #c82333;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
<!-- Contenedor del Dashboard -->
<div class="dashboard-container">
<!-- Header -->
<div class="form-header">
    <h1>Crear Nuevo Tablero</h1>
    <form method="post" action="" class="d-inline">
        <button type="submit" name="logout" class="btn btn-logout">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </button>
    </form>
</div>

<!-- Formulario para crear un tablero -->
<div class="form-container">
    <form action="create_board.php" method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Título del Tablero</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Escribe el título" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Contenido del Tablero</label>
            <textarea class="form-control" id="content" name="content" rows="4" placeholder="Escribe el contenido" required></textarea>
        </div>
        <div class="mb-3">
            <label for="visibility" class="form-label">Visibilidad del Tablero</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="is_private" name="visibility" value="private" required>
                <label class="form-check-label" for="is_private">Privado</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="is_public" name="visibility" value="public" required>
                <label class="form-check-label" for="is_public">Público</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Crear Tablero</button>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>© 2024 Paleta. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
