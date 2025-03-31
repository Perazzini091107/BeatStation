<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header('Location: login.php'); // Redireciona caso o usuário não esteja logado
    exit();
}

$conn = new mysqli("localhost", "root", "", "beatstation");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['post_id']);

    // Verifique se o post existe e se o usuário é o autor
    $user = $_SESSION['nome'];
    $sql = "SELECT user FROM posts WHERE idpost = $post_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['user'] === $user) {
            // Excluir o post
            $delete_sql = "DELETE FROM posts WHERE idpost = $post_id";
            if ($conn->query($delete_sql) === TRUE) {
                header('Location: Perfil.php'); // Redireciona de volta para o perfil após a exclusão
                exit();
            } else {
                echo "Erro ao excluir o post: " . $conn->error;
            }
        } else {
            echo "Você não tem permissão para excluir este post.";
        }
    } else {
        echo "Post não encontrado.";
    }
}

$conn->close();
?>
