<?php require 'navbar.php'; ?>
<?php
session_start();
include('database.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_SESSION['user_id']);
$erro = "";

// Recupera os dados do usuário antes de exibir o formulário
$query = "SELECT instagram, twitter, soundcloud FROM usuarios WHERE id = ?";
if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $instagram = $row['instagram'] ?? '';
        $twitter = $row['twitter'] ?? '';
        $soundcloud = $row['soundcloud'] ?? '';
    } else {
        $instagram = '';
        $twitter = '';
        $soundcloud = '';
    }
    mysqli_stmt_close($stmt);
}

// Função para validar os links das redes sociais
function validar_link($url, $plataforma) {
    $padroes = [
        'instagram'  => '/^(https?:\/\/)?(www\.)?instagram\.com\/[a-zA-Z0-9._]+\/?$/',
        'twitter'    => '/^(https?:\/\/)?(www\.)?(twitter|x)\.com\/[a-zA-Z0-9_]+\/?$/',
        'soundcloud' => '/^(https?:\/\/)?(www\.)?soundcloud\.com\/[a-zA-Z0-9-]+\/?$/'
    ];

    return preg_match($padroes[$plataforma], $url);
}

// Processa o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instagram = $_POST['instagram'] ?? '';
    $twitter = $_POST['twitter'] ?? '';
    $soundcloud = $_POST['soundcloud'] ?? '';

    // Validação dos links
    if ((!empty($instagram) && !validar_link($instagram, 'instagram')) ||
        (!empty($twitter) && !validar_link($twitter, 'twitter')) ||
        (!empty($soundcloud) && !validar_link($soundcloud, 'soundcloud'))) {
        $erro = "Por favor, insira links válidos das redes sociais!";
    } else {
        // Se os links forem válidos, atualizar no banco de dados
        $query = "UPDATE usuarios SET instagram = ?, twitter = ?, soundcloud = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "sssi", $instagram, $twitter, $soundcloud, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Redireciona para o perfil após salvar
        header("Location: perfil.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - BeatStation</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { padding: 0; margin: 0; box-sizing: border-box; font-family: "Poppins", sans-serif; }
        body { background-color: #181818; color: #fff; }
        .container { margin-top: 10px; padding: 50px 12px; text-align: center; }
        .settings-form { background: #333; padding: 30px; max-width: 500px; margin: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
        .settings-form h2 { color: #29fd53; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { font-size: 1rem; display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border-radius: 5px; border: none; background: #444; color: #fff; font-size: 1rem; }
        .form-group input:focus { background: #555; }
        .submit-btn { background: #29fd53; color: #222327; padding: 10px; border: none; border-radius: 5px; font-size: 1.1rem; cursor: pointer; width: 100%; font-weight: 600; }
        .submit-btn:hover { background: #fff; color: #29fd53; }
        .error { color: red; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>
<br>
<div class="container">
    <br>

    <div class="settings-form">
        <h2>Edit Social Networks</h2>

        <?php if (!empty($erro)): ?>
            <p class="error"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="instagram">Instagram</label>
                <input type="text" id="instagram" name="instagram" value="<?php echo htmlspecialchars($instagram); ?>">
            </div>
            <div class="form-group">
                <label for="twitter">Twitter</label>
                <input type="text" id="twitter" name="twitter" value="<?php echo htmlspecialchars($twitter); ?>">
            </div>
            <div class="form-group">
                <label for="soundcloud">SoundCloud</label>
                <input type="text" id="soundcloud" name="soundcloud" value="<?php echo htmlspecialchars($soundcloud); ?>">
            </div>
            
            <button style="margin-top: 10px;" type="submit" class="submit-btn">Salvar</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
