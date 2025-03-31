<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ob_start(); // Adiciona o buffer para capturar a saída

include('database.php'); // Certifique-se de que esse arquivo define $conn corretamente

// Inicializa $row como null para evitar erros
$row = null;
// Verifica se o usuário está logado e se há um ID válido na sessão
if (isset($_SESSION['id'])) {
    $id = intval($_SESSION['id']); // Pega o ID corretamente da sessão

    // Consulta segura usando prepared statements
    $query = "SELECT nome,usuarios,  FROM usuarios WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeatStation</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="website icon" type="png" href="img/NovoProjeto.png">
    <style>
        /* Seus estilos existentes */

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            list-style: none;
        }

        :root {
            --bg-color: #2c2c2c;
            --text-color: #fff;
            --main-color: #29fd53;
        }

        body {
            min-height: 100vh;
            background: var(--bg-color);
            color: var(--text-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 12px;
            text-align: center;
        }

        .header {
            position: fixed;
            width: 100%;
            top: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-color);
            padding: 28px 12%;
            transition: all .50s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            gap: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo i {
            color: var(--main-color);
            font-size: 29px;
            margin-right: 3px;
        }

        .logo span {
            color: var(--text-color);
            font-size: 1.7rem;
            font-weight: 600;
        }

        .navbar {
            display: flex;
            gap: 15px;
        }

        .navbar a {
            color: var(--text-color);
            font-size: 1.1rem;
            font-weight: 500;
            padding: 5px 0;
            margin: 0px 30px;
            transition: all .50s ease;
        }

        .navbar a:hover {
            color: var(--main-color);
        }

        .main {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 34px;
        }

        .main a {
            margin-right: 25px;
            margin-left: 10px;
            color: var(--text-color);
            font-size: 1.1rem;
            font-weight: 500;
            transition: all .50s ease;
        }

        .user {
            display: flex;
            align-items: center;
        }

        .user i {
            color: var(--main-color);
            font-size: 28px;
            margin-right: 7px;
        }

        .user a:hover {
            color: var(--main-color);
        }

        .register {
            background-color: var(--main-color);
            border-radius: 10px;
            padding: 7px 15px;
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .register:hover {
            background-color: #fff;
            color: var(--main-color);
        }

        .hero {
            padding: 150px 12px;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            color: var(--main-color);
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: var(--text-color);
        }

        .hero a {
            display: inline-block;
            background-color: var(--main-color);
            color: var(--text-color);
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .hero a:hover {
            background-color: #fff;
            color: var(--main-color);
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 50px;
        }

        .feature {
            width: 300px;
            background: var(--bg-color);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 15px;
            transition: transform 0.3s ease;

        }

        .feature:hover {
            transform: translateY(-10px);
        }

        .feature i {
            font-size: 40px;
            color: var(--main-color);
            margin-bottom: 15px;
        }

        .feature h3 {
            font-size: 1.5rem;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 1rem;
            color: var(--text-color);
        }

        .footer {
            background: #111;
            color: var(--text-color);
            padding: 20px 12px;
            text-align: center;
            margin-top: 50px;
        }

        .footer a {
            color: var(--main-color);
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #fff;
        }

        .beats-container {
            max-width: 1200px;
            margin: 10px auto 50px;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .beat {
            width: 300px;
            background: var(--bg-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .beat:hover {
            transform: translateY(-10px);
        }

        .beat img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .beat-info {
            padding: 15px;
            color: var(--text-color);
            text-align: center;
        }

        .beat-info h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--main-color);
        }

        .beat-info p {
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .beat-info a {
            display: inline-block;
            background-color: var(--main-color);
            color: var(--text-color);
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .beat-info a:hover {
            background-color: #fff;
            color: var(--main-color);
        }

        footer {
            background-color: #000;
            color: var(--text-color);
            text-align: center;
            padding: 20px 0;
        }

        .margin-top {
            margin-top: -40px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 85px;
            /* Distância abaixo do nome do usuário */
            left: 1155px;
            /* Alinha o menu com o ícone e o nome do usuário */
            background-color: var(--bg-color);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1000;
        }



        .dropdown-menu a {
            display: block;
            color: var(--text-color);
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 1.1rem;
            transition: background 0.3s ease;
            margin-bottom: 10px;
        }

        .dropdown-menu a:hover {
            background-color: var(--main-color);
        }

        .user {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user i {
            color: var(--main-color);
            font-size: 28px;
            margin-right: 7px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #29fd53;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgb(255, 255, 255);
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 10px;
        }

        .search-bar {
    display: flex;
    align-items: center;
    background: #1e1e1e;
    border: 2px solid var(--main-color);
    border-radius: 20px;
    padding: 8px 10px;
    width: 250px;
    transition: 0.3s ease-in-out;
}

.search-bar input {
    border: none;
    outline: none;
    background: transparent;
    color: var(--text-color);
    font-size: 1rem;
    flex: 1;
    padding: 5px;
}

.search-bar input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-bar button {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    color: var(--main-color);
    transition: color 0.3s;
}

.search-bar button:hover {
    color: #fff;
}

/* Responsividade */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }

    .navbar {
        flex-direction: column;
        gap: 10px;
    }

    .search-bar {
        width: 180px;
        padding: 5px 10px;
    }
}

    </style>
</head>

<body>

    <nav class="header">
        <div class="header">
            <a href="home.php" class="logo">
                <i class="ri-headphone-fill"></i>
                <span>Beat<span class="station" style="color: var(--main-color);">Station</span></span>
            </a>

            <ul class="navbar">
                <li><a href="home.php"><b>Home</b></a></li>
                <li><a href="Beats.php"><b>Beats</b></a></li>
                <li><a href="Samples.php"><b>Songs</b></a></li>
                <li><a href="Contato.php"><b>Contact</b></a></li>
            </ul>

        <!-- Barra de Pesquisa -->
<form class="search-bar" action="buscar.php" method="GET">
    <input type="text" name="query" placeholder="Search..." required>
    <button type="submit"><i class="ri-search-line"></i></button>
</form>



            <ul class="navbar-nav d-flex mb-2 mb-lg-0">
                <?php if (!isset($_SESSION['user_id'])) { ?>
                    <li class="nav-item">
                        <div class="main">

                            <a href="login.php" class="register"><i class="ri-user-fill"></i><b>Login</b></a>
                        </div>
                    </li>
                <?php } else { ?>
                    <div class="main">
                        <a href="postar.php" class="register"><b>Post</b></a>
                        <a class="user" onclick="toggleDropdown(event)">

                            <div class="user-avatar"> <b><?php echo strtoupper(substr($_SESSION['nome'], 0, 1))  ?></b></div>
                        </a>
                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu" id="dropdown-menu">
                            <a href="Perfil.php"><b>Profile</b></a>
                            <a href="settings.php"><b>Settings</b></a>
                            <a href="logout.php"><b>Logout</b></a>
                        </div>
                    </div>
                <?php } ?>
        </div>
        </div>
    </nav>

    <script>
        function toggleDropdown(event) {
            const dropdownMenu = document.getElementById('dropdown-menu');
            dropdownMenu.style.display = (dropdownMenu.style.display === 'block') ? 'none' : 'block';
            event.stopPropagation();
        }

        // Fecha o dropdown se o usuário clicar fora
        document.addEventListener('click', function(event) {
            const dropdownMenu = document.getElementById('dropdown-menu');
            if (!dropdownMenu.contains(event.target) && !event.target.closest('.user')) {
                dropdownMenu.style.display = 'none';
            }
        });
    </script>

</body>

</html>