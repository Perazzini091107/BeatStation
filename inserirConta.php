<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nome = $_POST['Nome'];
    $user = $_POST['username'];
    $Senha = $_POST['password'];
    $Admin = $_POST['admin'];
    $Email = $_POST['email'];

    // Validação dos campos
    if (empty($Nome) || empty($user) || empty($Senha) || empty($Email)) {
        echo "<script>alert('Todos os campos são obrigatórios.');</script>";
        echo "<script>location.href='Cadastro.php';</script>";
        exit();
    }

    // Verificar o comprimento do nome e do usuário
    if (strlen($Nome) > 12) {
        echo "<script>alert('O nome de usuário não pode ter mais de 12 caracteres.');</script>";
        echo "<script>location.href='Cadastro.php';</script>";
        exit();
    }

    if (strlen($user) > 12) {
        echo "<script>alert('O nome não pode ter mais de 12 caracteres.');</script>";
        echo "<script>location.href='Cadastro.php';</script>";
        exit();
    }

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Formato de e-mail inválido.');</script>";
        echo "<script>location.href='Cadastro.php';</script>";
        exit();
    }

    try {
        // Verificar se o e-mail já está cadastrado
        $sqlCheckEmail = "SELECT id FROM usuarios WHERE email = ?";
        $stmtCheckEmail = $conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->bind_param("s", $Email);
        $stmtCheckEmail->execute();
        $stmtCheckEmail->store_result();

        if ($stmtCheckEmail->num_rows > 0) {
            echo "<script>alert('Este e-mail já está sendo utilizado.');</script>";
            echo "<script>location.href='Cadastro.php';</script>";
            exit();
        }
        $stmtCheckEmail->close();

        // Verificar se o nome já está cadastrado
        $sqlCheckNome = "SELECT id FROM usuarios WHERE nome = ?";
        $stmtCheckNome = $conn->prepare($sqlCheckNome);
        $stmtCheckNome->bind_param("s", $Nome);
        $stmtCheckNome->execute();
        $stmtCheckNome->store_result();

        if ($stmtCheckNome->num_rows > 0) {
            echo "<script>alert('Este nome já está sendo utilizado.');</script>";
            echo "<script>location.href='Cadastro.php';</script>";
            exit();
        }
        $stmtCheckNome->close();

        // Criptografar a senha
        $SenhaHash = password_hash($Senha, PASSWORD_BCRYPT);

        // Inserir na tabela usuarios
        $conn->begin_transaction();
        $sqlUsuarios = "INSERT INTO usuarios (nome, email, usuarios, senha, admin) VALUES (?, ?, ?, ?, ?)";
        $stmtUsuarios = $conn->prepare($sqlUsuarios);

        if (!$stmtUsuarios) {
            throw new Exception("Erro ao preparar a tabela usuarios: " . $conn->error);
        }

        $stmtUsuarios->bind_param('sssss', $Nome, $Email, $user, $SenhaHash, $Admin);

        if (!$stmtUsuarios->execute()) {
            throw new Exception("Erro ao inserir dados na tabela usuarios: " . $stmtUsuarios->error);
        }

        $conn->commit();
        echo "<script>location.href='login.php';</script>";
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro: " . $e->getMessage();
    } finally {
        if (isset($stmtUsuarios)) {
            $stmtUsuarios->close();
        }
        $conn->close();
    }
}
?>

