<?php
// Habilitar errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /paleta/app/views/dashboard.php");
    exit();
}

// Incluir conexión a la base de datos y el modelo de Board
require_once '../app/config/db.php'; // Asegúrate de que este archivo tiene la configuración de tu DB
require_once '../app/models/Board.php';

// Crear la conexión a la base de datos
$db = (new DB())->connect();

// Crear una instancia de la clase Board para obtener los tableros públicos
$boardModel = new Board($db);
$publicBoards = $boardModel->getPublicBoards();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Paleta</title>
    <!-- Enlaces a Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <!-- Enlace a Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Barra de navegación -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Paleta <i class="fas fa-palette"></i></h1>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php"><i class="fas fa-home"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/paleta/app/views/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/paleta/app/views/register.php"><i class="fas fa-user-plus"></i> Registrarse</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-5">
        <h2 class="text-center mb-4">Tableros Públicos</h2>
        
        <?php if (count($publicBoards) > 0): ?>
            <div class="row">
                <?php foreach ($publicBoards as $board): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow border-light">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($board['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($board['content']); ?></p>
                                <a href="../app/views/view_board.php?id=<?php echo $board['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Ver Tablero
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                No hay tableros públicos disponibles en este momento.
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>© 2024 Paleta. Todos los derechos reservados.</p>
    </footer>

    <!-- Enlaces a Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
