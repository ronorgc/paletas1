<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Enlaces a Bootstrap 5 y Font Awesome para íconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/paleta/public/css/style.css">
</head>
<body class="bg-light">
    <!-- Contenedor principal -->
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-4">
            <div class="card shadow-sm p-4">
                <h2 class="text-center mb-4">Iniciar Sesión</h2>
                
                <!-- Mostrar mensaje si existe -->
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario de inicio de sesión -->
                <form action="/paleta/app/controllers/UserController.php?action=login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Correo electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p>¿No tienes cuenta? <a href="/paleta/app/views/register.php">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces a Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
