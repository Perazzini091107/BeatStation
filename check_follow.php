<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Erro: Usuário não logado.");
}

$conn = new mysqli("localhost", "root", "", "beatstation");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

$user_to_follow_name = $conn->real_escape_string($_GET['user']);
$user_id = $_SESSION['user_id']; // ID do usuário logado

// Buscar ID do usuário que está sendo seguido
$sql_get_id = "SELECT id FROM usuarios WHERE nome = '$user_to_follow_name'";
$result_get_id = $conn->query($sql_get_id);

if (!$result_get_id || $result_get_id->num_rows === 0) {
    die("Erro: Usuário não encontrado.");
}

$row = $result_get_id->fetch_assoc();
$user_to_follow = $row['id'];

// Verificar se já segue
$sql_check = "SELECT * FROM seguidores WHERE seguidor_id = '$user_id' AND seguido_id = '$user_to_follow'";
$result_check = $conn->query($sql_check);

if (!$result_check) {
    die("Erro na consulta SQL: " . $conn->error);
}

// Retornar apenas texto, sem HTML
if ($result_check->num_rows > 0) {
    echo "following"; // Já segue
} else {
    echo "not_following"; // Não segue
}

$conn->close();
?>
