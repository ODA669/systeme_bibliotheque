<?php
// 1. Incluir la configuración de la base de datos
include_once "include/config.php"; // $mysqli debe estar definido aquí
$mysqli = new mysqli($host, $username, $password, $database);
// 2. Obtener los datos del formulario
$titre = $_POST['titre'];
$auteur = $_POST['auteur'];
$datePublication = $_POST['datePublication'];
$nombrePages = $_POST['nombrePages'];

// 3. Preparar la consulta segura (Prepared Statement)
$stmt = $mysqli->prepare("INSERT INTO livres (titre, auteur, datePublication, nombrePages) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $titre, $auteur, $datePublication, $nombrePages);

// 4. Ejecutar la consulta
if ($stmt->execute()) {
    echo "Libro agregado correctamente";
} else {
    echo "Error al agregar el libro: " . $stmt->error;
}

// 5. Cerrar el statement
$stmt->close();

// 6. (Opcional) Redirigir al usuario a otra página después de agregar el libro
header("Location: employe_dashboard.php");
exit();
