<?php
session_start();
require_once '../config/db.php';
require_once '../models/BoardContent.php';

$db = (new DB())->connect();
$boardContentModel = new BoardContent($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $board_id = $_POST['board_id'];
    $content_type = $_POST['content_type'];
    
    if ($content_type == 'text') {
        $content = $_POST['content_text'];
    } elseif (in_array($content_type, ['image', 'pdf', 'video'])) {
        $content = uploadFile($_FILES['content_file']);
    } elseif (in_array($content_type, ['link', 'youtube'])) {
        $content = $_POST['content_link'];
    }

    $boardContentModel->create($board_id, $content_type, $content);
    
    header("Location: /paleta/app/views/dashboard.php?message=Contenido agregado exitosamente.");
    exit();
}

function uploadFile($file) {
    $targetDir = "../uploads/";
    $targetFile = $targetDir . basename($file["name"]);
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    } else {
        die("Error subiendo el archivo.");
    }
}
?>
