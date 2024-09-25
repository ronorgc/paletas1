<?php
session_start();
require_once '../config/db.php';
require_once '../models/BoardContent.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: /paleta/app/views/login.php?message=Debes iniciar sesión para editar contenido.");
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
$content = $boardContentModel->findById($content_id); // Obtener el contenido por ID

// Verificar si el contenido existe
if (!$content) {
    header("Location: /paleta/app/views/view_board.php?message=Contenido no encontrado.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el contenido editado
    $content_type = $_POST['content_type'];
    $content_value = '';

    // Manejo de archivos subidos
    if ($content_type === 'image' || $content_type === 'pdf' || $content_type === 'video') {
        $target_dir = "../../public/uploads/";
        $target_file = $target_dir . basename($_FILES["content"]["name"]);
        move_uploaded_file($_FILES["content"]["tmp_name"], $target_file);
        $content_value = basename($_FILES["content"]["name"]); // Guardar solo el nombre del archivo
    } else {
        $content_value = $_POST['content'];
    }

    // Actualizar el contenido
    $boardContentModel->update($content_id, $content_type, $content_value);
    header("Location: /paleta/app/views/view_board.php?id={$content['board_id']}&message=Contenido actualizado exitosamente.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 30px auto;
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
        .btn-logout {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }
        .btn-logout:hover {
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
    </style>
</head>
<body>

<!-- Contenedor del Formulario -->
<div class="form-container">
    <div class="form-header">
        <h1>Editar Contenido</h1>
    </div>

    <form action="edit_content.php?content_id=<?php echo $content_id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="content_type" class="form-label">Tipo de Contenido:</label>
            <select name="content_type" id="content_type" class="form-select" required>
                <option value="text" <?php if ($content['content_type'] === 'text') echo 'selected'; ?>>Texto</option>
                <option value="image" <?php if ($content['content_type'] === 'image') echo 'selected'; ?>>Imagen</option>
                <option value="pdf" <?php if ($content['content_type'] === 'pdf') echo 'selected'; ?>>PDF</option>
                <option value="video" <?php if ($content['content_type'] === 'video') echo 'selected'; ?>>Video</option>
                <option value="youtube" <?php if ($content['content_type'] === 'youtube') echo 'selected'; ?>>Video de YouTube</option>
                <option value="link" <?php if ($content['content_type'] === 'link') echo 'selected'; ?>>Enlace</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Contenido:</label>
            <?php if (in_array($content['content_type'], ['image', 'pdf', 'video'])): ?>
                <input type="file" name="content" id="content" class="form-control" accept="image/*,application/pdf,video/*">
            <?php else: ?>
                <textarea name="content" id="content_text" class="form-control" rows="4" placeholder="Introduce el texto o URL del contenido"><?php echo htmlspecialchars($content['content']); ?></textarea>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary w-100">Actualizar Contenido</button>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>© 2024 Paleta. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
