<?php
session_start();
$conn = new mysqli("localhost", "root", "", "beatstation");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

if (isset($_GET['user'])) {
    $user = $conn->real_escape_string($_GET['user']);

    // Conta quantas pessoas seguem o usuário (não quantas ele segue)
    $sql = "SELECT COUNT(*) AS total FROM seguidores 
            INNER JOIN usuarios ON seguidores.seguido_id = usuarios.id
            WHERE usuarios.nome = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = $result->fetch_assoc();
        echo $data['total'];
    } else {
        echo "0"; // Em caso de erro
    }

    $stmt->close();
} else {
    echo "0"; // Nenhum usuário especificado
}

$conn->close();
?>


