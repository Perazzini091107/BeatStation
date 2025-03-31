<?php
session_start();
require 'db_connect.php'; // Arquivo de conexão com o banco

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        die("Erro: Usuário não autenticado.");
    }

    $user_id = $_SESSION['user_id']; // Obtém o ID do usuário autenticado
    $nome = trim($_POST['username']); // Agora a variável correta é $nome
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $facebook = trim($_POST['facebook']);
    $instagram = trim($_POST['instagram']);
    $twitter = trim($_POST['twitter']);

    try {
        // Inicia a query SQL
        $sql = "UPDATE usuarios SET nome=?, email=?, facebook=?, instagram=?, twitter=?";

        // Se a senha foi preenchida, adiciona ao UPDATE
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password=?";
        }

        $sql .= " WHERE id=?";

        // Prepara a query
        $stmt = $conn->prepare($sql);

        if (!empty($password)) {
            $stmt->bind_param("ssssssi", $nome, $email, $facebook, $instagram, $twitter, $password_hash, $user_id);
        } else {
            $stmt->bind_param("sssssi", $nome, $email, $facebook, $instagram, $twitter, $user_id);
        }

        // Executa a query
        if ($stmt->execute()) {
            echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='Settings.php';</script>";
        } else {
            throw new Exception("Erro ao atualizar perfil.");
        }

        // Fecha a conexão
        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        die("Erro: " . $e->getMessage());
    }

} else {
    header("Location: Settings.php");
    exit();
}
?>
