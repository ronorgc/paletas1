<?php
// Iniciar sesión si ya está registrada
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../views/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Enlaces a Bootstrap 5 y Font Awesome para los íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/paleta/public/css/style.css">
</head>
<body class="bg-light">
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-5">
                <h2 class="text-center mb-4">Registro de Usuario</h2>

                <!-- Mostrar mensajes de error o éxito -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php elseif (isset($_GET['message'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario de Registro -->
                <form action="/paleta/app/controllers/UserController.php?action=register" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label"><i class="fas fa-user"></i> Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Tu nombre de usuario" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label"><i class="fas fa-lock"></i> Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repite tu contraseña" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> Registrarse</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces a Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
