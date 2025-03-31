<?php
$servername = "localhost"; // Ou IP do servidor
$username = "root"; // Usuário do banco de dados
$password = ""; // Senha do banco de dados
$database = "beatstation"; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
