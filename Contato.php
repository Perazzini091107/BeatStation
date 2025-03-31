<?php require 'navbar.php'; ?>
<?php
session_start();
include('database.php'); 

if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    
    $query = "SELECT admin FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (isset($row['admin']) && trim($row['admin']) === 'admin123') {
            if (basename($_SERVER['PHP_SELF']) !== 'ContactAdmin.php') { // Evita redirecionamento se já estiver na página
                header("Location: ContactAdmin.php");
                exit();
            }
        }
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - BeatStation</title>
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

        .contact-info {
            margin-top: 30px;
            text-align: center;
            font-size: 1rem;
        }

        .contact-info a {
            color: var(--main-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-info a:hover {
            color: #1cc645;
        }
    </style>
</head>
<body style="background-color: #181818">  
<div class="container">
    <h2>Contact Us</h2>
    <form action="message.php" method="POST">
        <div class="form-group">
            <div style="text-align: left;">
            <label for="name"><b>Name:</b></label>
            </div>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <div style="text-align: left;">
            <label for="email"><b>Email:</b></label>
            </div>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <div style="text-align: left;">
            <label for="message"><b>Message:</b></label>
            </div>
            <textarea id="men" name="men" placeholder="Write your message here..." required></textarea>
        </div>
        <div class="form-group">
            <button type="submit">To send</button>
        </div>
    </form>

    <div class="contact-info">
        <p>Or contact us directly via email: <a href="mailto:beatstationweb@gmail.com">beatstationweb@gmail.com</a></p>

    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
