<?php
session_start();
require 'navbar.php';
$conn = new mysqli("localhost", "root", "", "beatstation");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

if (!isset($_GET['user'])) {
    die("Usuário não especificado!");
}

$user = $conn->real_escape_string($_GET['user']);

// Buscar seguidores do usuário
$query = "SELECT u.nome FROM seguidores s 
          JOIN usuarios u ON s.seguidor_id = u.id 
          WHERE s.seguindo_id = (SELECT id FROM usuarios WHERE nome = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguidores de <?php echo htmlspecialchars($user); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .followers-list {
            list-style: none;
            padding: 0;
        }
        .followers-list li {
            background: #2c2c2c;
            margin: 10px auto;
            padding: 15px;
            width: 250px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h2>Seguidores de <?php echo htmlspecialchars($user); ?></h2>
    <ul class="followers-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['nome']); ?></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>

<?php
$conn->close();
?>
