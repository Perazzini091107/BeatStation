<?php
$host = 'localhost';
$dbname = 'beatstation';
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
} else {

}
?>


