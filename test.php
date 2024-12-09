<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "pidedos";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "Conexión exitosa";
?>