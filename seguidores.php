<?php require 'navbar.php'; ?>
<?php
session_start();
$user = $_SESSION['nome'];

$conn = new mysqli("localhost", "root", "", "beatstation");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

// Obtém o ID do usuário logado
$sql_user_id = "SELECT id FROM usuarios WHERE nome = ?";
$stmt = $conn->prepare($sql_user_id);
$stmt->bind_param("s", $user);
$stmt->execute();
$result_user_id = $stmt->get_result();
$row_user_id = $result_user_id->fetch_assoc();
$user_id = $row_user_id['id'] ?? 0;

$seguidores = [];

if ($user_id) {
    // Buscar seguidores
    $sql_seguidores = "SELECT u.id, u.nome, u.usuarios FROM seguidores s 
                        JOIN usuarios u ON s.seguidor_id = u.id 
                        WHERE s.seguido_id = ?";
    $stmt = $conn->prepare($sql_seguidores);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result_seguidores = $stmt->get_result();

    while ($row = $result_seguidores->fetch_assoc()) {
        $seguidores[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguidores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .user-card {
            display: flex;
            align-items: center;
            background: #222;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .user-card:hover {
            transform: translateY(-5px);
        }
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #29fd53;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 15px;
        }
        .user-info {
            text-align: left;
            flex-grow: 1;
        }
        .user-info a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .user-info a:hover {
            text-decoration: none;
        }
        .btn-go-profile {
            padding: 5px 15px;
            background-color: #29fd53;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-go-profile:hover {
            background-color: rgb(35, 212, 71);
        }
    </style>
</head>
<body>
    <br>
    <br>
    <br>
    <br>
    <div class="container">
        <h2 style="color:#29fd53; font-size:2rem">Followers</h2>
        <br>
        <?php if (!empty($seguidores)) : ?>
            <?php foreach ($seguidores as $seguidor) : ?>
                <div class="user-card">
                    <a href="perfil2.php?user=<?php echo urlencode($seguidor['nome']); ?>" class="user-avatar">
                        <?php echo strtoupper(substr($seguidor['nome'], 0, 1)); ?>
                    </a>
                    <div class="user-info">
                        <a href="perfil2.php?user=<?php echo urlencode($seguidor['nome']); ?>">
                            <p><?php echo htmlspecialchars($seguidor['nome']); ?></p>
                            <p style="color:#777; font-size: 15px;margin-top: -5px;">@<?php echo htmlspecialchars($seguidor['usuarios']); ?></p>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div style="text-align: center;font-size:90px; color:#1f1f1f;background-color: #333; border-radius:15px;padding:99px ">
                
                <i class="bi bi-music-note-beamed"></i>
              </div>
        <?php endif; ?>
    </div>
</body>
</html>