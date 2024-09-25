<?php

require_once '../config/db.php';   // Incluir la conexión de la base de datos
require_once '../models/User.php'; // Incluir el modelo del usuario

class UserController {
    private $db;
    private $user;

    public function __construct() {
        // Crear una nueva instancia de la conexión de base de datos
        $database = new DB(); // Utiliza la clase DB correctamente
        $this->db = $database->connect();

        // Crear una instancia del modelo User y pasarle la conexión de base de datos
        $this->user = new User($this->db);
    }

    // Método para registrar un nuevo usuario
    public function register() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        try {
            // Llama al método save() del modelo User para registrar el usuario
            $this->user->save($username, $password, $email);

            // Redirige a la página de login con un mensaje de éxito
            header("Location: /paleta/app/views/login.php?message=" . urlencode("Registro exitoso. Por favor inicie sesión."));
            exit(); // Asegúrate de salir después de la redirección

        } catch (Exception $e) {
            // Redirige a la página de registro con un mensaje de error
            header("Location: /paleta/app/views/register.php?error=" . urlencode($e->getMessage()));
            exit(); // Asegúrate de salir después de la redirección
        }
    }

    // Método para iniciar sesión
    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            // Verifica las credenciales del usuario
            $user = $this->user->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Iniciar sesión
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirigir a dashboard.php
                header("Location: /paleta/app/views/dashboard.php");
                exit();
            } else {
                throw new Exception("Credenciales inválidas.");
            }
        } catch (Exception $e) {
            // Redirige a la página de login con un mensaje de error
            header("Location: /paleta/app/views/login.php?message=" . urlencode($e->getMessage()));
            exit();
        }
    }
}

// Verificar la acción que se desea ejecutar
if (isset($_GET['action'])) {
    $controller = new UserController();

    if ($_GET['action'] == 'register') {
        $controller->register();
    } elseif ($_GET['action'] == 'login') {
        $controller->login();
    }
}
