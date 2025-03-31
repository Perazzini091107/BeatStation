<?php require 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trap Beats - BeatStation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://unpkg.com/wavesurfer.js"></script>
    <style>
         .feed-container {
            max-width: 800px;
            margin: 100px auto 50px;
            background: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: var(--text-color);           
            word-wrap: break-word;
        }

        .feed-title {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--main-color);
            text-align: center;
        }

        .post {
            background: #222;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .post:hover {
            transform: translateY(-5px);
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--main-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 10px;
        }

        .user-info {
            font-size: 1rem;
        }

        .user-info .username {
            font-weight: bold;
            color: var(--text-color);
        }

        .user-info .timestamp {
            font-size: 0.9rem;
            color: #aaa;
        }

        .waveform-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #444;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .waveform {
            flex-grow: 1;
            margin: 0 10px;
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
    </style>
</head>

<body style="background-color: #181818">
    <br>
    <div class="feed-container">
        <h1 class="feed-title">Boom Bap Lo-Fi</h1>
        <?php
        $conn = new mysqli("localhost", "root", "", "beatstation");

        if ($conn->connect_error) {
            die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
        }

        $sql = "SELECT idpost, user, content, caminho, timestamp FROM posts WHERE sub_category = 'Boom Bap Lo-Fi' AND main_category='beat' ORDER BY timestamp DESC";
        $stmt = $conn->query($sql);

        if ($stmt->num_rows > 0) {
            while ($row = $stmt->fetch_assoc()) {
                echo '<div class="post">';
                echo '<div class="post-header">';
                echo '<a href="perfil2.php?user=' . urlencode($row['user']) . '" class="user-avatar">' . strtoupper(substr($row['user'], 0, 1)) . '</a>';
                echo '<div class="user-info">';
                echo '<a href="perfil2.php?user=' . urlencode($row['user']) . '" class="username">' . htmlspecialchars($row['user']) . '</a><br>';                
                echo '<span class="timestamp">' . htmlspecialchars(date("d M Y, H:i", strtotime($row['timestamp']))) . '</span>';
                echo '</div>';
                echo '</div>';
                echo '<div class="post-content">' . nl2br(htmlspecialchars($row['content']));
                echo '<br>';

                if (!empty($row['caminho'])) {
                    $audioId = $row['idpost'];
                    echo '<div class="waveform-container">';
                    echo '<button class="play-button" data-id="' . $audioId . '"><i class="bi bi-play-fill"></i></button>';
                    echo '<div id="waveform-' . $audioId . '" class="waveform" data-audio="' . htmlspecialchars($row['caminho']) . '"></div>';
                    echo '<a href="' . htmlspecialchars($row['caminho']) . '" download class="download-button">Download</a>';
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div style="text-align: center;font-size:90px; color:#1f1f1f;background-color: #333; border-radius:15px;padding:99px; width: 800px; margin-left:-20px ">
                    <i class="bi bi-music-note-beamed"></i>
                </div>
              <p style="text-align: center;margin-top: -125px;color:#222"><b>No publications</b> </p>';
        }

        $conn->close();
        ?>

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