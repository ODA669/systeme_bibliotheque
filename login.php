<?php
session_start();
include_once "include/config.php";

if (isset($_POST['login'])) {

    $username_email = trim($_POST['username']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $mysqli = new mysqli($host, $username, $password, $database);
    if ($mysqli->connect_error) {

        die("Conexión fallida: " . $mysqli->connect_error);
    }

    // Primero intentamos encontrarlo como cliente
    $stmt = $mysqli->prepare("SELECT client_id AS id, username, mot_de_passe, actif FROM client WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username_email, $username_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $user = $result->fetch_assoc();
        if ($user['actif'] != 1) {

            $_SESSION['error'] = "El cliente no está activo.";
            header("Location: login.php");
            exit();
        }
        if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = 'client'; // <-- indicador de tipo

            header("Location: client_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: login.php");
            exit();
        }
    } else {
        // Si no es cliente, intentamos como empleado
        $stmt = $mysqli->prepare("SELECT id, username, mot_de_passe, actif  FROM employes  WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username_email, $username_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($user['actif'] != 1) {
                $_SESSION['error'] = "El empleado no está activo.";
                header("Location: login.php");
                exit();
            }
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = 'employe'; // <-- indicador de tipo

                header("Location: employe_dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Usuario no encontrado.";
            header("Location: login.php");
            exit();
        }
    }

    $stmt->close();
    $mysqli->close();
}
