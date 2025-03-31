<?php
ob_start(); // Inicia o buffer de saída
session_start(); // Garante que a sessão foi iniciada

require 'navbar.php';
$conn = new mysqli("localhost", "root", "", "beatstation");

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    die("Você precisa estar logado para acessar esta página.");
}

// Obtém o nome do usuário da URL
if (isset($_GET['user'])) {
    $user = $conn->real_escape_string($_GET['user']);
} else {
    die("Nenhum usuário especificado!");
}

// Se o usuário acessando for o mesmo que está logado, redireciona para Perfil.php
if ($user === $_SESSION['nome']) {
    ob_clean(); // Limpa qualquer saída anterior
    header("Location: Perfil.php");
    exit();
}

// Obtém informações do perfil
$query_perfil = "SELECT id,nome,email,senha,idpost ,usuarios,data,soundcloud, instagram,twitter, admin FROM usuarios WHERE nome = ?";
$stmt = $conn->prepare($query_perfil);
$stmt->bind_param("s", $user);
$stmt->execute();
$result_perfil = $stmt->get_result();
$perfil = $result_perfil->fetch_assoc();

$soundcloud = $row_user['soundcloud'] ?? '';
$instagram = $row_user['instagram'] ?? '';
$twitter = $row_user['twitter'] ?? '';

if (!$perfil) {
    die("Perfil não encontrado!");
}

// Obtém posts do usuário
$query_posts = "SELECT * FROM posts WHERE user = ? ORDER BY timestamp DESC";
$stmt = $conn->prepare($query_posts);
$stmt->bind_param("s", $user);
$stmt->execute();
$result_posts = $stmt->get_result();

$audioId = uniqid();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - BeatStation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/wavesurfer.js"></script>
    <style>
        :root {
            --primary-color: #29fd53;
            --secondary-color: #2c2c2c;
            --muted-color: #b3b3b3;
            --text-color: #ffffff;
        }

        body,
        h1,
        h2,
        h3,
        p,
        button {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #181818;
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }



        .profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: var(--primary-color);
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            margin-right: 20px;
        }

        .profile-header {
            position: relative;
            /* Define o contêiner de referência */
            background: var(--secondary-color);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 40px;
        }

        /* Posiciona fixamente a área de detalhes dentro do header */
        .profile-details {
            position: absolute;
            left: 150px;
            /* ajuste conforme necessário */
            top: 50%;
            transform: translateY(-50%);
        }

        /* Remova margens negativas nos elementos internos */
        .profile-details h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            /* Ajuste conforme necessário */
        }

        .profile-details p {
            font-size: 1rem;
            color: var(--muted-color);
            margin-top: 5px;
            /* Ajuste para dar um pouco de espaço acima do @usuario */
        }


        .profile-actions button {
            background: var(--primary-color);
            color: var(--text-color);
            padding: 12px 20px;
            border-radius: 6px;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .profile-actions button:hover {
            background: rgb(255, 255, 255);
            color: var(--primary-color);
        }

        .main-container {
            display: flex;
            align-items: flex-start;
            gap: 30px;
        }

        .sidebar {
            width: 250px;
            background: var(--secondary-color);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            height: 215px;
            flex-shrink: 0;
        }

        .sidebar .menu a {
            display: block;
            color: var(--text-color);
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 1.1rem;
            transition: background 0.3s ease;
            margin-bottom: 10px;
        }

        .sidebar .menu a:hover {
            background: var(--primary-color);
        }

        .feed {
            flex-grow: 1;
        }

        .feed .post {
            background: var(--secondary-color);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            word-wrap: break-word;
        }

        .feed .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .feed .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgb(255, 255, 255);
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 10px;
        }

        .feed .user-info .username {
            font-weight: bold;
            color: var(--text-color);
        }

        .feed .user-info .timestamp {
            font-size: 0.9rem;
            color: #aaa;
        }

        .feed .post-content {
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
            max-width: 100%;
            background-color: #1f1f1f;
            border-radius: 15px;
            padding: 10px;
        }

        .waveform-container {
            display: flex;
            align-items: center;
            margin-top: -10px;
            gap: 8px;
            background-color: #444;
            padding: 6px;
            border-radius: 20px;
            border: 10px solid #1f1f1f;
            margin-top: -27px;
        }

        .waveform {
            flex-grow: 1;
            margin-left: 10px;
        }

        .play-button,
        .download-button {
            background-color: var(--main-color);
            border: none;
            color: white;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .play-button i,
        .download-button i {
            font-size: 1.2rem;
        }

        .profile-details {
            position: absolute;
            left: 150px;
            /* ajuste conforme necessário */
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            /* Adicionado */
            align-items: center;
            /* Adicionado */
        }

        .social-links {
            display: flex;
            /* Adicionado */
            margin-left: 10px;
            /* Adicionado para espaçamento */
            font-size: 30px;
            gap: 15px;
        }

        .social-icon {
            color: var(--text-color);
            margin-left: 5px;
            /* Espaçamento entre os ícones */
        }
    </style>
</head>

<body>
    <br><br>
    <br>
    <br>
    <div class="profile-header">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($perfil['nome'], 0, 1)); ?>
        </div>

        <div class="profile-details">
            <div style="margin-left: -10px;">
                <h2 ><?php echo htmlspecialchars($perfil['nome']); ?></h2>
                <p style="margin-top:-10px ;">@<?php echo htmlspecialchars($perfil['usuarios']); ?></p>
            </div>
            <!-- Ícones das redes sociais -->
            <div class="social-links">
            <?php if (!empty($perfil['twitter'])): ?>
                    <a href="<?php echo htmlspecialchars($perfil['twitter']); ?>" target="_blank" class="social-icon">
                    <i class="bi bi-twitter-x"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($perfil['instagram'])): ?>
                    <a href="<?php echo htmlspecialchars($perfil['instagram']); ?>" target="_blank" class="social-icon">
                        <i class="bi bi-instagram"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($perfil['soundcloud'])): ?>
                    <a href="<?php echo htmlspecialchars($perfil['soundcloud']); ?>" target="_blank" class="social-icon">
                    <i class="fa fa-soundcloud"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile-actions">
            <b style="font-size: 20px;">
                <span style="color: #29fd53;" id="followers-count">...</span> Followers
            </b>

            <button style="margin-left: 10px;" id="follow-btn" onclick="toggleFollow('<?php echo $user; ?>')">
                Carregando...
            </button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            checkFollowStatus("<?php echo $user; ?>");
            updateFollowersCount("<?php echo $user; ?>");
        });

        function checkFollowStatus(username) {
            fetch("check_follow.php?user=" + username)
                .then(response => response.text())
                .then(data => {
                    let followBtn = document.getElementById("follow-btn");
                    followBtn.innerText = data.trim() === "following" ? "Unfollow" : "Follow";
                })
                .catch(error => console.error("Erro na verificação de seguidores:", error));
        }

        function updateFollowersCount(username) {
            fetch("get_followers_count.php?user=" + username)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("followers-count").innerText = data.trim();
                })
                .catch(error => console.error("Erro ao buscar contagem de seguidores:", error));
        }

        function toggleFollow(username) {
            fetch("follow.php?user=" + username, {
                    method: "POST"
                })
                .then(response => response.text())
                .then(data => {
                    let followBtn = document.getElementById("follow-btn");
                    if (data.trim() === "following") {
                        followBtn.innerText = "Unfollow";
                    } else if (data.trim() === "not_following") {
                        followBtn.innerText = "Follow";
                    } else {
                        alert("Erro ao seguir usuário: " + data);
                    }
                    updateFollowersCount(username);
                })
                .catch(error => console.error("Erro na requisição:", error));
        }
    </script>

    <div class="main-container">
        <div class="sidebar">
            <div class="menu">
                <a href="perfil2.php?user=<?php echo urlencode($user); ?>">Profile</a>
                <a href="Beats2.php?user=<?php echo urlencode($user); ?>">Beats</a>
                <a href="Songs2.php?user=<?php echo urlencode($user); ?>">Songs</a>
            </div>
        </div>

        <div class="feed">
            <?php
            if ($result_posts->num_rows > 0) {
                while ($row = $result_posts->fetch_assoc()) {
                    echo '<div class="post">';
                    echo '<div class="post-header">';
                    echo '<div class="user-avatar">' . strtoupper(substr($row['user'], 0, 1)) . '</div>';
                    echo '<div class="user-info">';
                    echo '<span class="username">' . htmlspecialchars($row['user']) . '</span><br>';
                    echo '<span class="timestamp">' . date("d M Y, H:i", strtotime($row['timestamp'])) . '</span>';
                    echo '</div></div>';
                    echo '<div class="post-content">' . nl2br(htmlspecialchars($row['content'])) . '<br><br></div>';
                    if (!empty($row['caminho'])) {
                        $audioId = uniqid(); // Gera um ID único para cada áudio
                        echo '<div class="waveform-container">';
                        echo '<button class="play-button" data-id="' . $audioId . '"><i class="bi bi-play-fill"></i></button>';
                        echo '<div id="waveform-' . $audioId . '" class="waveform" data-audio="' . htmlspecialchars($row['caminho']) . '"></div>';
                        echo '<a href="' . htmlspecialchars($row['caminho']) . '" download class="download-button">Download</a>';
                        echo '</div>';
                    }


                    echo '</div>';
                }
            } else {
                echo '';
                echo '<div style="text-align: center;font-size:90px; color:#1f1f1f;background-color: #2c2c2c; border-radius:15px;padding:99px ">
                
                <i class="bi bi-music-note-beamed"></i>
              </div>';

                echo '<p style="text-align: center;margin-top: -125px;color:#222"><b>No publications</b> </p>';
                echo '';
            }
            $conn->close();
            ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.play-button');

            buttons.forEach(button => {
                const id = button.getAttribute('data-id');
                const container = document.getElementById('waveform-' + id);
                const audioPath = container.getAttribute('data-audio');

                const wavesurfer = WaveSurfer.create({
                    container: container,
                    waveColor: 'lightgray',
                    progressColor: '#29fd53',
                    barWidth: 2,
                    height: 50
                });

                wavesurfer.load(audioPath);
                wavesurfer.setVolume(1);
                button.addEventListener('click', () => {
                    const icon = button.querySelector('i');
                    if (wavesurfer.isPlaying()) {
                        wavesurfer.pause();
                        icon.className = 'bi bi-play-fill';
                    } else {
                        wavesurfer.play();
                        icon.className = 'bi bi-pause-fill';
                    }
                });
            });
        });
    </script>
</body>

</html>
<?php ob_end_flush(); ?>