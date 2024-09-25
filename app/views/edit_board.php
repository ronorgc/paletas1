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
$board_id = $_GET['id']; // Obtener el ID del tablero de la URL

// Obtener los datos del tablero actual
$board = $boardModel->findById($board_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Asegurarnos de que el valor sea 1 o 0
    $is_private = isset($_POST['is_private']) ? 1 : 0;
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    // Actualizar el tablero
    if ($boardModel->update($board_id, $title, $content, $is_private, $is_public)) {
        header("Location: dashboard.php?message=Tablero actualizado exitosamente.");
        exit();
    } else {
        echo "Error al actualizar el tablero.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tablero</title>
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
        <h1>Editar Tablero</h1>
        <form method="post" action="" class="d-inline">
            <button type="submit" name="logout" class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>

    <!-- Formulario para editar un tablero -->
    <div class="form-container">
        <form action="edit_board.php?id=<?php echo $board_id; ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Título del Tablero</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($board['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenido del Tablero</label>
                <textarea class="form-control" id="content" name="content" rows="4" required><?php echo htmlspecialchars($board['content']); ?></textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_private" name="is_private" 
                       <?php echo $board['is_private'] ? 'checked' : ''; ?> onchange="toggleCheckbox('is_public', this)">
                <label class="form-check-label" for="is_private">Privado</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" 
                       <?php echo $board['is_public'] ? 'checked' : ''; ?> onchange="toggleCheckbox('is_private', this)">
                <label class="form-check-label" for="is_public">Público</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Actualizar Tablero</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2024 Paleta. Todos los derechos reservados.</p>
    </footer>
</div>

<script>
function toggleCheckbox(otherCheckboxId, currentCheckbox) {
    const otherCheckbox = document.getElementById(otherCheckboxId);
    if (currentCheckbox.checked) {
        otherCheckbox.checked = false;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
