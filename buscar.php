
<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'navbar.php';
include('database.php'); // Conexão com o banco de dados

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    // Consulta segura usando Prepared Statements
    $sql = "SELECT id, nome, usuarios, soundcloud, instagram, twitter FROM usuarios WHERE nome LIKE ? OR usuarios LIKE ?";
    $stmt = $conn->prepare($sql);
    $param = "%" . $query . "%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: white;
            text-align: center;
            padding: 0 15px;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #333;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .search-title {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #29fd53;
        }

        .user-card {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Ajusta a posição do botão */
            background: #222;
            padding: 15px;
            margin-bottom: 20px;
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
            font-size: 1rem;
            text-align: left;
            flex-grow: 1; /* Garante que o conteúdo ocupe o espaço restante */
        }

        .user-info .username {
            font-weight: bold;
            color: white;
            font-size: 1.1rem;
        }

        .user-info .social-links {
            font-size: 0.9rem;
        }

        .social-links a {
            margin: 0 5px;
            color: #29fd53;
            text-decoration: none;
        }

        .social-links a:hover {
            text-decoration: underline;
        }

        .no-results {
            color: #aaa;
            font-size: 1.2rem;
            padding: 20px;
            background: #333;
            border-radius: 8px;
        }

        /* Responsividade */
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .user-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .user-avatar {
                margin-bottom: 10px;
            }
        }

        .btn-go-profile {
            padding: 5px 15px;
            background-color:#29fd53;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-go-profile:hover {
            background-color:rgb(35, 212, 71);
        }
    </style>
</head>
<body>
    <br><br><br><br><br>
    <div class="container">
        <h1 class="search-title">Search Results</h1>
        <?php if (isset($result) && $result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="user-card">
                    <a href="perfil2.php?user=<?php echo urlencode($row['nome']); ?>" class="user-avatar">
                        <?php echo strtoupper(substr($row['nome'], 0, 1)); ?>
                    </a>

                    <div class="user-info">
                        <a href="perfil2.php?user=<?php echo urlencode($row['nome']); ?>" class="username">
                            <p><?php echo htmlspecialchars($row['nome']); ?></p>
                            <p style="color:#777; font-size: 15px;margin-top: -5px;">@<?php echo htmlspecialchars($row['usuarios']); ?></p>
                        </a>
                    </div>

                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div style="text-align: center;font-size:90px; color:#1f1f1f;background-color: #333; border-radius:15px;padding:99px;width:800px; margin-left: -20px;">
                <i class="bi bi-music-note-beamed"></i>
            </div>
            <p style="text-align: center;margin-top: -125px;color:#222"><b>No profiles found</b> </p>
        <?php endif; ?>
    </div>
</body>
</html>
