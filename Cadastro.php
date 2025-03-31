<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - BeatStation</title>
    <link rel="website icon" type="png"
         href="img/NovoProjeto.png"
        >
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
        .register-container {
            width: 100%;
            max-width: 450px;
            background: #2c2c2c;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            height: 600px;
            margin-top: 120px;


        }
        .register-container h1 {
            text-align: center;
            margin-bottom: 0px;
            color: #29fd53;
        }
        .register-container form {
            display: flex;
            flex-direction: column;
        }
        .register-container label {
            font-size: 1rem;
            margin-bottom: 5px;
        }
        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            padding: 10px;
            font-size: 1rem;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background: #444;
            color: #fff;
        }
        .register-container input[type="submit"] {
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
        .register-container input[type="submit"]:hover {
            background: #fff;
            color: #29fd53;
        }
        .register-container .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-container .login-link a {
            color: #29fd53;
            text-decoration: none;
        }
        .register-container .login-link a:hover {
            text-decoration: underline;
        }
        .back-button {
            display: block;
            margin: 20px auto;
            text-align: center;
            background: #444;
            color: #2c2c2c;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
            background:#29fd53;
            color: #222327;
        }
        .back-button:hover {
            background:rgb(255, 255, 255);
            color: #29fd53;
        } 
        label{
            margin-top:10px;
        }
    </style>
</head>
<body>
<br>
<br>
    <div class="register-container">
        <h1>Register</h1>
        <form action="inserirConta.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="Nome" name="Nome" placeholder="Enter your username" required>

            <label for="username">Name</label>
            <input type="text" id="username" name="username" placeholder="Enter your name" required>

            <label for="email">Admin Password</label>
            <input type="text" id="admin" name="admin" placeholder="Are you an admin? If yes, enter the password given to you" >

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <input type="submit" value="Register">
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">To enter</a></p>
        </div>
        <a href="home.php" class="back-button">To go back</a>
    </div>
</body>
</html>
