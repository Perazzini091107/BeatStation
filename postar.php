<?php require 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - BeatStation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .container {
            max-width: 800px;
            margin: 100px auto 50px;
            background: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: var(--text-color);
        }

        .container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--main-color);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #555;
            border-radius: 5px;
            background: #222;
            color: var(--text-color);
            font-size: 1rem;
        }

        .form-group textarea {
            resize: none;
            height: 150px;
        }

        .form-group button {
            width: 100%;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            background: var(--main-color);
            color: var(--text-color);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background: #1cc645;
        }

        /* Estilos para alinhar os botões de rádio */
        #input-option356 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            /* Espaço entre os itens */
        }

        .radio {
            box-sizing: border-box;
            width: 160px;
            height: 50px;
            position: relative;
            background-color: rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            /* Animação */
        }

        .radio label {
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .radio label span {
            z-index: 1;
            color: white;
            font-weight: bold;
        }

        .radio input[type=radio] {
            all: unset;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
        }

        .toggle-container {
            display: flex;
            border: 2px solid white;
            border-radius: 10px;
            overflow: hidden;
            width: 200px;
            margin: 0 auto;
            /* Aqui está a mudança para centralizar */
        }

        .toggle-option {
            flex: 1;
            text-align: center;
            padding: 5px;
            cursor: pointer;
            user-select: none;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .selected {
            background-color: #1cc645;
            color: white;
        }

        /* Estiliza o label para parecer um botão circular */
        .custom-file-upload {
            display: inline-block;
            width: 60px;
            /* Largura do botão */
            height: 60px;
            /* Altura do botão */
            border-radius: 50%;
            /* Torna o botão circular */
            background-color: #444;
            /* Cor de fundo cinza */
            color: white;
            /* Cor do ícone */
            text-align: center;
            /* Centraliza o ícone horizontalmente */
            line-height: 60px;
            /* Centraliza o ícone verticalmente */
            cursor: pointer;
            /* Cursor de ponteiro ao passar o mouse */
            font-size: 24px;
            /* Tamanho do ícone */
        }

        .custom-file-upload:hover {
            background-color: #333;
            /* Cor de fundo ao passar o mouse */
        }

        .upload-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            margin-right: 20px;
            background-color: #222;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #555;
        }
    </style>
</head>

<body style="background-color: #181818">
    <br>
    <br>
    <div class="container">
        <h2>Post</h2>

        <form action="InserirPost.php" method="POST" enctype="multipart/form-data">

            <div class="toggle-container">
                <div class="toggle-option" id="beat" onclick="selectOption('beat')"><b>Beat</b></div>
                <div class="toggle-option" id="musica" onclick="selectOption('musica')"><b>Song</b></div>
            </div>

            <script>
                function selectOption(option) {
                    document.getElementById('beat').classList.remove('selected');
                    document.getElementById('musica').classList.remove('selected');
                    document.getElementById(option).classList.add('selected');
                    document.getElementById('main_category').value = option; // Atualiza a categoria principal
                }

                // Selecione "Beat" automaticamente ao carregar a página
                document.addEventListener('DOMContentLoaded', function() {
                    selectOption('beat');
                });
            </script>


            <div class="form-group">
                <div style="text-align: left;">
                    <label for="user"><b>Usurname</b></label>
                </div>
                <input type="text" id="user" name="user" value="<?php echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Usuário'; ?>" readonly>
            </div>

            <div class="form-group">
                <div style="text-align: left;">
                    <label for="user"><b>Type</b></label>
                </div>
                <div id="input-option356">
                    <?php
                    $beats = [
                        "Trap Beats" => "#8000FF",
                        "Boom Bap" => "#6F4E37",
                        "Lo-fi Hip Hop Beat" => "rgb(149, 183, 196)",
                        "Drill Beat" => "#8B0000",
                        "Afrobeat" => "#FF8300",
                        "Dancehall Beat" => "#FFD700",
                        "Boom Bap Lo-Fi" => "#808000",
                        "Reggaeton Beat" => "#FF007F",
                        "EDM Beat" => "#007FFF",
                        "R&B Beat" => "#9966CC",
                        "Other" => "#808080"
                    ];

                    foreach ($beats as $name => $color) {
                        $id = strtolower(str_replace(" ", "", $name));
                        echo "<div class='radio' id='div_$id' style='border: 1px solid $color;'>
                        <label for='beat$id'>  
                            <input type='radio' name='sub_category' id='beat$id' value='$name' onclick='changeColor(\"div_$id\", \"$color\")' required>
                            <span>$name</span>
                        </label>
                      </div>";
                    }

                    ?>
                </div>
            </div>


            <div class="form-group">
                <div style="text-align: left;">
                    <label for="user"><b>Message</b></label>
                </div>
                <textarea id="message" name="message" placeholder="Write your post message here..." required></textarea>
            </div>

            <!-- Campo de upload de áudio -->
            <div style="text-align: left;">
                <div style="text-align: left;">
                    <label for="audio"><b>Audio</b></label>
                </div>
                <div class="upload-container" style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px; flex-grow: 1;">
                        <span id="file-icon" style="color: white; display: none; font-size: 40px;">
                            <i class="bi bi-file-earmark-music"></i>
                        </span>
                        <div style="display: flex; flex-direction: column; word-break: break-word; overflow-wrap: break-word; flex-grow: 1;">
                            <span id="file-info" style="color: white;"></span>
                            <span style="font-size: 12px;" id="file-info2" style="color: white;"></span>
                        </div>
                    </div>
                    <input type="file" id="audio" name="audio" accept="audio/*" required style="display: none;">
                    <label for="audio" class="custom-file-upload">
                        <i class="bi bi-music-note-list"></i>
                    </label>
                </div>
            </div>


            <br>

            <script>
                document.getElementById('audio').addEventListener('change', function(event) {
                    var file = event.target.files[0]; // Obtém o arquivo selecionado
                    if (file) {
                        var fileName = file.name; // Nome do arquivo
                        var fileSize = (file.size / (1024 * 1024)).toFixed(2); // Tamanho em MB

                        document.getElementById('file-info').textContent = `${fileName}`;
                        document.getElementById('file-info2').textContent = `${fileSize} MB`;
                        document.getElementById('file-icon').style.display = 'inline'; // Exibe o ícone
                    } else {
                        document.getElementById('file-info').textContent = '';
                        document.getElementById('file-info2').textContent = '';
                        document.getElementById('file-icon').style.display = 'none'; // Esconde o ícone
                    }
                });
            </script>






            <!-- Campos ocultos para enviar as categorias -->
            <input type="hidden" id="main_category" name="main_category" value="outro"> <!-- Categoria principal (Beat ou Música) -->


            <div class="form-group">
                <button type="submit">To send</button>
            </div>
        </form>
    </div>

    <script>
        function changeColor(divId, color) {
            document.querySelectorAll('.radio').forEach(div => {
                div.style.backgroundColor = "rgba(0, 0, 0, 0.2)";
            });

            document.getElementById(divId).style.backgroundColor = color;
            document.getElementById('sub_category').value = document.querySelector(`#${divId} input`).value; // Atualiza a subcategoria
        }
    </script>
</body>

</html>

<?php include 'footer.php'; ?>