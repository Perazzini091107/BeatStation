<?php require 'navbar.php'; ?>
<?php 
session_start();
include('database.php'); 


// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) { // Verifica se há um ID de usuário na sessão
    $id = intval($_SESSION['user_id']); // Pega o ID da sessão

    // Consulta segura para pegar os dados
    $query = "SELECT nome, usuarios, email, senha FROM usuarios WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $nome = $row['nome'] ?? '';
            $usuario = $row['usuarios'] ?? ''; 
            $email = $row['email'] ?? '';
            $senha = $row['senha'] ?? '';
        } else {
            // Caso não haja resultados
            $nome = '';
            $usuario = '';
            $email = '';
            $senha = '';
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - BeatStation</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        :root {
            --bg-color:  #2c2c2c;
            --text-color: #fff;
            --main-color: #29fd53;
        }
        body {
            background-color: #181818;
            color: var(--text-color);
        }
        .container {
            margin-top: 10px;
            padding: 50px 12px;
            text-align: center;
        }
        .settings-form {
            background: #333;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .settings-form h2 {
            color: var(--main-color);
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            font-size: 1rem;
            color: var(--text-color);
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            outline: none;
            background: #444;
            color: var(--text-color);
            font-size: 1rem;
        }
        .form-group input:focus {
            background: #555;
        }
        .back-btn {
            display: block;
            width: 100%;
            background:#29fd53;
            color: #222327;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        .back-btn:hover {
     background: #fff;
        color: #29fd53;
        }
    </style>
</head>
<body>
<br>
<div class="container">
    <div class="settings-form">
        <h2>Settings</h2>

        <!-- Nome -->
        <div class="form-group">
        <label for="usuario">Username</label>
            <input type="text" id="nome" name="nome" 
                value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>" readonly>
        </div>

        <!-- Usuário -->
        <div class="form-group">
            <label for="nome">Name</label>
            <input type="text" id="usuario" name="usuario" 
                value="<?php echo isset($usuario) ? htmlspecialchars($usuario) : ''; ?>" readonly>
        </div>


        <!-- Email -->
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" 
                value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" readonly>
        </div>

        <!-- Senha (exibir como "********" por segurança) -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" 
                value="<?php echo isset($senha) ? htmlspecialchars($senha) : ''; ?>" readonly>
        </div>

        <!-- Botão de voltar -->
        <button class="back-btn" onclick="window.history.back();">Back</button>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
