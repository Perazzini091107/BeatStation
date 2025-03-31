<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        // Conectar ao banco de dados
        $conn = new mysqli("localhost", "root", "", "beatstation");

        // Verificar erro de conexão
        if ($conn->connect_error) {
            error_log("Erro ao conectar ao banco de dados: " . $conn->connect_error);
            die("Erro ao conectar ao banco de dados.");
        }

        // Preparar a query de exclusão
        $sql = "DELETE FROM contato WHERE idcontato = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Erro na preparação da query: " . $conn->error);
            die("Erro na preparação da query.");
        }

        $stmt->bind_param("i", $id);

        // Executar a query
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo 'success';
            } else {
                error_log("Nenhum registro foi deletado. ID: $id");
                echo 'not_found';
            }
        } else {
            error_log("Erro ao executar a query: " . $stmt->error);
            echo 'error';
        }

        // Fechar conexões
        $stmt->close();
        $conn->close();
    } else {
        echo 'invalid';
    }
} else {
    echo 'invalid_request';
}
?>


