<?php
$username = "root";
$password = "";
$host = "localhost";
$database = "matricebiblio";

// Crear la conexión
$conn = new mysqli($host, $username, $password, $database);

// Verificar conexión
// if ($conn->connect_error) {
//     die("Conexión fallida: " . $conn->connect_error);
// } else {
//     echo "Conexión exitosa a la base de datos!";
// }
