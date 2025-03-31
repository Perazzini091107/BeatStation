<?php
require 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST["user"] ?? '');
    $content = trim($_POST["message"] ?? '');
    $main_category = trim($_POST["main_category"] ?? '');
    $sub_category = trim($_POST["sub_category"] ?? '');

    $audioNome = $_FILES['audio']['name'];
    $audioTmp = $_FILES['audio']['tmp_name'];
    $uploadDir = 'uploads/';

    // Verifica se o diretório existe e tenta criá-lo se não existir
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Gera um nome único para evitar sobrescrita
    $extensao = pathinfo($audioNome, PATHINFO_EXTENSION);
    $nomeUnico = uniqid('audio_') . '.' . $extensao;
    $destino = $uploadDir . $nomeUnico; // Caminho correto a ser salvo

    if ($_FILES['audio']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($audioTmp, $destino)) {
            // Insere no banco de dados com o caminho correto
            $sql = "INSERT INTO posts (user, content, main_category, sub_category, caminho) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $user, $content, $main_category, $sub_category, $destino);

            if ($stmt->execute()) {
                header("Location: Perfil.php?success=1");
                exit();
            } else {
                echo "Erro ao enviar o post: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Erro ao mover o arquivo para o diretório de destino.";
        }
    } else {
        echo "Erro no upload do arquivo. Código de erro: " . $_FILES['audio']['error'];
    }
}

$conn->close();
