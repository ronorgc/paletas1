<?php
session_start();
require_once '../config/db.php';
require_once '../models/Board.php';
require_once '../models/BoardContent.php';

// Crear conexión a la base de datos
$db = (new DB())->connect();

// Verificar si el parámetro 'id' está presente en la URL
if (!isset($_GET['id'])) {
    header("Location: /paleta/app/views/error.php?message=ID de tablero no especificado.");
    exit();
}

$board_id = $_GET['id']; // Obtener el ID del tablero de la URL
$boardModel = new Board($db); // Crear instancia de Board pasando la conexión a la base de datos
$boardContentModel = new BoardContent($db); // Crear instancia del modelo BoardContent

$board = $boardModel->findById($board_id); // Buscar el tablero por ID
$contents = $boardContentModel->getAllByBoardId($board_id); // Obtener todos los contenidos del tablero

// Verificar si el tablero existe
if (!$board) {
    header("Location: /paleta/app/views/error.php?message=Tablero no encontrado.");
    exit();
}

// Verificar si el tablero es privado y si el usuario tiene acceso
if (!$board['is_public'] && (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $board['user_id'])) {
    header("Location: /paleta/app/views/login.php?message=No tienes acceso a este tablero.");
    exit();
}

// Incrementar el contador de visitas del tablero
$boardModel->incrementViews($board_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($board['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .board-container {
            max-width: 900px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .board-title {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }
        .board-description {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .content-section {
            margin-top: 20px;
        }
        .content-section h3 {
            font-size: 1.5rem;
            color: #495057;
        }
        .content-item {
            margin-bottom: 20px;
        }
        .content-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        .content-card img, .content-card video {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #dee2e6;
        }
        .content-card-body {
            padding: 15px;
        }
        .content-card-body p {
            font-size: 1rem;
            color: #495057;
        }
        .content-card-body a {
            color: #007bff;
            text-decoration: none;
        }
        .content-card-body a:hover {
            text-decoration: underline;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .btn-custom {
            background-color: #007bff;
            border: none;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container board-container">
    <h1 class="board-title"><?php echo htmlspecialchars($board['title']); ?></h1>
    <p class="board-description"><?php echo nl2br(htmlspecialchars($board['content'])); ?></p>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $board['user_id']): ?>
        <a href="add_content.php?board_id=<?php echo $board['id']; ?>" class="btn btn-primary mb-3">Añadir Contenido</a>
    <?php endif; ?>

    <div class="content-section">
        <h3>Contenido del Tablero:</h3>
        <?php if (empty($contents)): ?>
            <div class="alert alert-warning" role="alert">
                No hay contenido en este tablero.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($contents as $content): ?>
                    <div class="col-md-6">
                        <div class="content-item">
                            <div class="content-card">
                                <?php if ($content['content_type'] === 'text'): ?>
                                    <div class="content-card-body">
                                        <p><?php echo nl2br(htmlspecialchars($content['content'])); ?></p>
                                    </div>
                                <?php elseif ($content['content_type'] === 'image'): ?>
                                    <img src="/paleta/public/uploads/<?php echo htmlspecialchars($content['content']); ?>" alt="Imagen del contenido">
                                    <div class="content-card-body">
                                        <p>Imagen del Tablero</p>
                                    </div>
                                <?php elseif ($content['content_type'] === 'pdf'): ?>
                                    <div class="content-card-body">
                                        <a href="/paleta/public/uploads/<?php echo htmlspecialchars($content['content']); ?>" target="_blank">Ver PDF</a>
                                    </div>
                                <?php elseif ($content['content_type'] === 'video'): ?>
                                    <video controls>
                                        <source src="/paleta/public/uploads/<?php echo htmlspecialchars($content['content']); ?>" type="video/mp4">
                                    </video>
                                    <div class="content-card-body">
                                        <p>Video del Tablero</p>
                                    </div>
                                <?php elseif ($content['content_type'] === 'youtube'): ?>
                                    <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($content['content']); ?>" frameborder="0" allowfullscreen></iframe>
                                    <div class="content-card-body">
                                        <p>Video de YouTube</p>
                                    </div>
                                <?php elseif ($content['content_type'] === 'link'): ?>
                                    <div class="content-card-body">
                                        <a href="<?php echo htmlspecialchars($content['content']); ?>" target="_blank"><?php echo htmlspecialchars($content['content']); ?></a>
                                    </div>
                                <?php endif; ?>

                                <!-- Botones de editar y eliminar -->
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $board['user_id']): ?>
                                    <div class="content-card-body">
                                        <div class="action-buttons">
                                            <a href="edit_content.php?content_id=<?php echo $content['id']; ?>" class="btn-custom">Editar</a>
                                            <a href="delete_content.php?content_id=<?php echo $content['id']; ?>&board_id=<?php echo $board_id; ?>" class="btn-custom" onclick="return confirm('¿Estás seguro de que deseas eliminar este contenido?');">Eliminar</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
