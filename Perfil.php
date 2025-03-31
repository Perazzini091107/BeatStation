<?php require 'navbar.php'; ?>
<?php
session_start();
$user = $_SESSION['nome'];

$conn = new mysqli("localhost", "root", "", "beatstation");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

$sql = "SELECT idpost, user, content, caminho, timestamp FROM posts WHERE user = '$user' ORDER BY timestamp DESC";
$result = $conn->query($sql);
// Obtém o ID do usuário logado
$sql_user_id = "SELECT id FROM usuarios WHERE nome = '$user'";
$result_user_id = $conn->query($sql_user_id);
$row_user_id = $result_user_id->fetch_assoc();
$user_id = $row_user_id['id'] ?? 0;
// Buscar redes sociais do usuário
$sql_user = "SELECT soundcloud, instagram, twitter FROM usuarios WHERE nome = '$user'";
$result_user = $conn->query($sql_user);
$row_user = $result_user->fetch_assoc();
$soundcloud = $row_user['soundcloud'] ?? '';
$instagram = $row_user['instagram'] ?? '';
$twitter = $row_user['twitter'] ?? '';

if ($user_id) {
    // Contar quantos usuários ele segue
    $sql_seguindo = "SELECT COUNT(*) AS seguindo FROM seguidores WHERE seguidor_id = $user_id";
    $result_seguindo = $conn->query($sql_seguindo);
    $row_seguindo = $result_seguindo->fetch_assoc();
    $seguindo = $row_seguindo['seguindo'] ?? 0;

    // Contar quantos seguidores ele tem
    $sql_seguidores = "SELECT COUNT(*) AS seguidores FROM seguidores WHERE seguido_id = $user_id";
    $result_seguidores = $conn->query($sql_seguidores);
    $row_seguidores = $result_seguidores->fetch_assoc();
    $seguidores = $row_seguidores['seguidores'] ?? 0;
} else {
    $seguindo = 0;
    $seguidores = 0;
}
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

        .profile-header {
            background: var(--secondary-color);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 40px;
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

        .profile-details h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .profile-details p {
            font-size: 1rem;
            color: var(--muted-color);
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
            height: 340px;
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

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
        }

        .delete-button:hover {
            background: darkred;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 0px;
        }

        .social-links a {
            font-size: 28px;
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <br>
    <br>
    <div class="profile-header">
        <div class="profile-info">
            <div class="profile-details">
                <?php
                $initial = strtoupper(substr($user, 0, 1));
                ?>
                <div class="profile-avatar"><?php echo $initial; ?></div>
                <h2 style="margin-top:-79px; margin-left:100px"><?php echo htmlspecialchars($user); ?> </h2>
                <?php
                $sql_user = "SELECT usuarios FROM usuarios WHERE nome = '$user'";
                $result_user = $conn->query($sql_user);
                $row_user = $result_user->fetch_assoc();
                ?>
                <p style="margin-top:-12px;margin-left:100px">@<?php echo htmlspecialchars($row_user['usuarios'] ?? 'Usuário não encontrado'); ?></p>

            </div>

        </div>
        <div class="social-links">
            <?php if ($twitter) : ?>
                <a class="social-link" data-url="<?php echo $twitter; ?>" href="#">
                    <i class="bi bi-twitter-x"></i>
                </a>
            <?php endif; ?>

            <?php if ($instagram) : ?>
                <a class="social-link" data-url="<?php echo $instagram; ?>" href="#">
                    <i class="bi bi-instagram"></i>
                </a>
            <?php endif; ?>

            <?php if ($soundcloud) : ?>
                <a class="social-link" data-url="<?php echo $soundcloud; ?>" href="#">
                    <i class="fa fa-soundcloud"></i>
                </a>
            <?php endif; ?>
        </div>


        <p style="margin-left: 320px; font-size: 20px;">
            <a href="seguidores.php"><b style="color: #29fd53;"><?php echo $seguidores; ?></b> <b style="color: white;"> Followers</b></a> |
            <a href="seguindo.php"><b style="color: #29fd53;"><?php echo $seguindo; ?></b> <b style="color: white;">Following</b></a>
        </p>
        <div class="profile-actions">
            <a href="editar_perfil.php">
                <button><b>Edit Social Networks</b></button>
            </a>
        </div>
    </div>

    <div class="main-container">
        <div class="sidebar">
            <div class="menu">
                <a href="Perfil.php">My Profile</a>
                <a href="MyBeats.php">My Beats</a>
                <a href="MySongs.php">My Songs</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <div class="feed">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $audioId = $row['idpost'];
                    echo '<div class="post" style="position: relative;">'; // Adicionando position relative aqui
                    echo '<div class="post-header">';
                    // Exibe o avatar do usuário
                    echo '<div class="user-avatar">' . strtoupper(substr($row['user'], 0, 1)) . '</div>';
                    // Informações do usuário e data
                    echo '<div class="user-info">';
                    echo '<span class="username">' . htmlspecialchars($row['user']) . '</span><br>';
                    echo '<span class="timestamp">' . date("d M Y, H:i", strtotime($row['timestamp'])) . '</span>';
                    echo '</div>';
                    // Botão de exclusão
                    echo '<form method="POST" action="delete_post2.php" style="position: absolute; top: 10px; right: 10px;">';
                    echo '<input type="hidden" name="post_id" value="' . $row['idpost'] . '">';
                    echo '<button type="submit" class="delete-button"><i class="bi bi-trash"></i></button>';
                    echo '</form>';
                    echo '</div>'; // Fim da post-header
                    echo '<div class="post-content">' . nl2br(htmlspecialchars($row['content'])) . '<br><br></div>';
                    if (!empty($row['caminho'])) {
                        echo '<div class="waveform-container">';
                        echo '<button class="play-button" data-id="' . $audioId . '"><i class="bi bi-play-fill"></i></button>';
                        echo '<div id="waveform-' . $audioId . '" class="waveform" data-audio="' . htmlspecialchars($row['caminho']) . '"></div>';
                        echo '<a href="' . htmlspecialchars($row['caminho']) . '" download class="download-button">Download</a>';
                        echo '</div>';
                    }
                    echo '</div>'; // Fim do post
                }
            } else {
                echo '<div style="text-align: center;font-size:90px; color:#1f1f1f;background-color: #2c2c2c; border-radius:15px;padding:99px ">
            <i class="bi bi-music-note-beamed"></i>
          </div>';
                echo '<p style="text-align: center;margin-top: -125px;color:#222"><b>No publications</b></p>';
            }
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.social-link').forEach(link => {
                link.addEventListener('click', function(event) {
                    let url = this.getAttribute('data-url').trim();
                    if (url) {
                        window.open(url, '_blank');
                    }
                });
            });
        });
    </script>
</body>

</html>