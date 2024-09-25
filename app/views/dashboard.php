<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /paleta/app/views/login.php?message=Por favor, inicie sesión.");
    exit();
}

// Cerrar sesión
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: /paleta/app/views/login.php?message=Has cerrado sesión exitosamente.");
    exit();
}

require_once '../config/db.php';
require_once '../models/Board.php';

$database = new DB();
$db = $database->connect();
$boardModel = new Board($db);

$user_id = $_SESSION['user_id'];
$boards = $boardModel->getAllByUser($user_id);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .dashboard-header {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .dashboard-header h1 {
            margin: 0;
        }
        .dashboard-header .btn-logout {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .dashboard-header .btn-logout:hover {
            background-color: #c82333;
        }
        .dashboard-header .btn-logout i {
            margin-right: 5px;
        }
        .board-table {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .board-actions i {
            cursor: pointer;
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

<!-- Contenedor del Dashboard -->
<div class="dashboard-container">

    <!-- Header -->
    <div class="dashboard-header">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <form method="post" action="" class="d-inline">
            <button type="submit" name="logout" class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>

    <!-- Contenido del Dashboard -->
    <div class="container">
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tus Tableros</h2>
            <a href="create_board.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Crear nuevo tablero
            </a>
        </div>

        <div class="board-table">
            <?php if (count($boards) > 0): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Visitas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($boards as $board): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($board['title']); ?></td>
                                <td><?php echo htmlspecialchars($board['views']); ?></td>
                                <td class="board-actions">
                                    <a href="view_board.php?id=<?php echo $board['id']; ?>" class="text-info me-2" title="Ver"><i class="fas fa-eye"></i></a>
                                    <a href="edit_board.php?id=<?php echo $board['id']; ?>" class="text-warning me-2" title="Editar"><i class="fas fa-edit"></i></a>
                                    <a href="add_content.php?board_id=<?php echo $board['id']; ?>" class="text-primary me-2" title="Añadir Contenido"><i class="fas fa-plus-square"></i></a>
                                    <a href="delete_board.php?id=<?php echo $board['id']; ?>" class="text-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este tablero?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No tienes tableros creados. <a href="create_board.php">Crea uno nuevo</a></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2024 Paleta. Todos los derechos reservados.</p>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
