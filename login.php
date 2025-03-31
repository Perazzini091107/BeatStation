<link rel="website icon" type="png" href="img/NovoProjeto.png">
<?php

// Incluir o arquivo de conexão com o banco de dados
require_once 'database.php';

// Variável para armazenar mensagens de erro ou sucesso
$error = '';

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar se o usuário existe no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Obter os dados do usuário
        $user = mysqli_fetch_assoc($result);

        // Verificar a senha (assumindo que está armazenada como hash)
        if (password_verify($senha, $user['senha'])) {
            // Login bem-sucedido
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nome'] = $user['nome']; 
            header("Location: home.php"); // Redirecionar para a área logada
            exit;
        } else {
            $error = "Senha incorreta.";
        }
    } else {
        $error = "Usuário não encontrado.";
    }
}

mysqli_close($conn);
?>
<style>
    body {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #181818;
        color: #fff;
        font-family: "Poppins", sans-serif;
    }
    .login-container {
        width: 100%;
        max-width: 400px;
        background: #333;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    .login-container h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #29fd53;
    }
    .login-container form {
        display: flex;
        flex-direction: column;
    }
    .login-container label {
        font-size: 1rem;
        margin-bottom: 5px;
    }
    .login-container input[type="text"],
    .login-container input[type="password"] {
        padding: 10px;
        font-size: 1rem;
        margin-bottom: 15px;
        border: none;
        border-radius: 5px;
        background: #444;
        color: #fff;
    }
    .login-container input[type="submit"] {
        background: #29fd53;
        color: #222327;
        padding: 10px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .login-container input[type="submit"]:hover {
        background: #fff;
        color: #29fd53;
    }
    .error {
        color: red;
        text-align: center;
        margin-top: 10px;
    }
    .register-link {
        text-align: center;
        margin-top: 15px;
    }
    .register-link a {
        color: #29fd53;
        text-decoration: none;
    }
    .register-link a:hover {
        text-decoration: underline;
    }
    .back-button {
        display: block;
        margin: 20px auto;
        text-align: center;
        background: #29fd53;
        color: #222327;
        padding: 10px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .back-button:hover {
        background: #fff;
        color: #29fd53;
    }

     .error {
        color: red;
        text-align: center;
        margin-top: 10px;
    }
</style>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" required>

            <label for="senha">Password</label>
            <input type="password" id="senha" name="senha" placeholder="Enter your password" required>
            <br>
            <input type="submit" value="Login">
            
            <!-- Exibir mensagens de erro abaixo do botão -->
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="Cadastro.php">Register</a></p>
        </div>
        <a href="home.php" class="back-button">Back</a>
    </div>
</body>
</html>
