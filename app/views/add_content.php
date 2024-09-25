<?php
session_start();
require_once '../config/db.php';
require_once '../models/Board.php';
require_once '../models/BoardContent.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: /paleta/app/views/login.php?message=Debes iniciar sesión para agregar contenido.");
    exit();
}

$db = (new DB())->connect();

// Obtener el ID del tablero
if (!isset($_GET['board_id'])) {
    header("Location: /paleta/app/views/add_content.php?message=ID de tablero no especificado.");
    exit();
}

$board_id = $_GET['board_id'];
$boardModel = new Board($db);
$board = $boardModel->findById($board_id);

// Verificar si el tablero pertenece al usuario logueado
if (!$board || $board['user_id'] != $_SESSION['user_id']) {
    header("Location: /paleta/app/views/add_content.php?message=No tienes permiso para agregar contenido a este tablero.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el contenido agregado
    $content_type = $_POST['content_type'];
    $content = '';

    // Subir archivo si es necesario
    if ($content_type === 'image' || $content_type === 'pdf' || $content_type === 'video') {
        $target_dir = "../../public/uploads/";
        $target_file = $target_dir . basename($_FILES["content"]["name"]);
        
        if (move_uploaded_file($_FILES["content"]["tmp_name"], $target_file)) {
            $content = basename($_FILES["content"]["name"]);
        } else {
            header("Location: /paleta/app/views/add_content.php?message=Error al subir el archivo.");
            exit();
        }
    } else {
        // Obtener el contenido de texto, enlace o YouTube
        $content = $_POST['content'];
    }

    $boardContentModel = new BoardContent($db);
    $boardContentModel->create($board_id, $content_type, $content);

    header("Location: /paleta/app/views/view_board.php?id=$board_id&message=Contenido agregado exitosamente.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Contenido a Tablero</title>
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
    <h1><?php echo htmlspecialchars($board['title']); ?></h1>
    <form method="post" action="" class="d-inline">
        <button type="submit" name="logout" class="btn btn-logout">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </button>
    </form>
</div>


<!-- Formulario para crear un tablero -->
<div class="form-container">
<form action="add_content.php?board_id=<?php echo $board_id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="content_type" class="form-label">Tipo de Contenido:</label>
            <select name="content_type" id="content_type" class="form-select" required>
                <option value="text">Texto</option>
                <option value="image">Imagen</option>
                <option value="pdf">PDF</option>
                <option value="video">Video</option>
                <option value="youtube">Video de YouTube</option>
                <option value="link">Enlace</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Contenido:</label>
            <input type="file" name="content" id="content" class="form-control" accept="image/*,application/pdf,video/*">
            <textarea name="content" id="content_text" class="form-control" rows="4" placeholder="Introduce el texto o URL del contenido" style="display: none;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Contenido</button>
    </form>
</div> 
<!-- Footer -->
<footer>
    <p>© 2024 Paleta. Todos los derechos reservados.</p>
</footer>

<script>
    const contentTypeSelect = document.getElementById('content_type');
    const contentInputFile = document.querySelector('input[type="file"]');
    const contentInputText = document.getElementById('content_text');

    contentTypeSelect.addEventListener('change', function() {
        if (this.value === 'text' || this.value === 'youtube' || this.value === 'link') {
            contentInputFile.style.display = 'none';
            contentInputText.style.display = 'block';
        } else {
            contentInputFile.style.display = 'block';
            contentInputText.style.display = 'none';
        }
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
