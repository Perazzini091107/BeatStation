<?php
require 'database.php';
session_start();

// Criar conexão com o banco de dados
$servername = "localhost";
$username = "root";  // Substitua pelo seu usuário do banco de dados
$password = "";      // Substitua pela sua senha do banco de dados
$dbname = "beatstation";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $men = trim($_POST["men"] ?? '');

    if (!empty($name) && !empty($email) && !empty($men)) {
        $stmt = $conn->prepare("INSERT INTO contato (name, email, men) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $men);
            if ($stmt->execute()) {
                header("Location: Contato.php");
                exit();
            } else {
                echo "Erro ao enviar o post: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Erro ao preparar a query.";
        }
    } else {
        echo "Todos os campos são obrigatórios!";
    }
}

$conn->close();
?>
