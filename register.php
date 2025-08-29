<?php

session_start();
include_once "include/config.php"; // $mysqli debe estar definido aquí

$mysqli = new mysqli($host, $username, $password, $database);
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recibir datos del formulario
    $username     = trim($_POST['username']);
    $email        = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $prenom       = trim($_POST['prenom']);
    $nom          = trim($_POST['nom']);
    $adresse      = trim($_POST['adresse']);
    $tipo         = $_POST['tipo']; // client o employee

    // Validación básica
    if (empty($username) || empty($email) || empty($mot_de_passe)) {
        $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
        header("Location: register_form.php");
        exit();
    }

    // Verificar si username o email ya existen
    if ($tipo === "client") {
        $check = $mysqli->prepare("SELECT client_id FROM client WHERE username=? OR email=?");
    } else {
        $check = $mysqli->prepare("SELECT id FROM employes WHERE username=? OR email=?");
    }
    if (!$check) {
        die("Error en prepare(): " . $mysqli->error);
    }
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Le nom d'utilisateur ou l'email existe déjà.";
        header("Location: register_form.php");
        exit();
    }
    $check->close();

    // Hashear contraseña
    $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Preparar inserción
    if ($tipo === "client") {
        $stmt = $mysqli->prepare("INSERT INTO client 
            (username, prenom, nom, email, mot_de_passe, actif, adresse, date_de_creation) 
            VALUES (?, ?, ?, ?, ?, 1, ?, NOW())");
    } elseif ($tipo === "employee") {
        $stmt = $mysqli->prepare("INSERT INTO employes 
            (username, prenom, nom, email, mot_de_passe, actif, adresse, date_de_creation) 
            VALUES (?, ?, ?, ?, ?, 1, ?, NOW())");
    } else {
        $_SESSION['error'] = "Type de compte non valide.";
        header("Location: register_form.php");
        exit();
    }

    if (!$stmt) {
        die("Error en prepare(): " . $mysqli->error);
    }

    $stmt->bind_param("ssssss", $username, $prenom, $nom, $email, $hashedPassword, $adresse);
    // var_dump($username, $prenom, $nom, $email, $hashedPassword, $adresse);

    // if ($stmt->execute()) {
    //     $_SESSION['success'] = "Utilisateur enregistré avec succès. Vous pouvez maintenant vous connecter.";

    //     header("Location: index.php"); // Redirige a login

    //     exit(); // Siempre exit después de header
    // } else {
    //     $_SESSION['error'] = "Erreur lors de l'enregistrement : " . $stmt->error;
    //     header("Location: register_form.php");
    //     exit();
    // }
    // if ($stmt->execute()) {
    //     $_SESSION['success'] = "Utilisateur enregistré avec succès. Vous pouvez maintenant vous connecter.";

    //     if ($tipo === "client") {
    //         header("Location: employe_dashboard.php"); // Solo los clientes van al dashboard
    //     } else {
    //         header("Location: index.php"); // Los empleados van al login
    //     }

    //     exit(); // Siempre exit después de header
    // } else {
    //     $_SESSION['error'] = "Erreur lors de l'enregistrement : " . $stmt->error;
    //     header("Location: register_form.php");
    //     exit();
    // }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Utilisateur enregistré avec succès.";

        // Solo redirigir a dashboard si estamos creando un cliente desde el dashboard
        if (isset($_POST['tipo']) && strtolower($_POST['tipo']) === 'client') {
            header("Location: employe_dashboard.php"); // dashboard
        } else {
            header("Location: index.php"); // login para empleados o registro externo
        }

        exit();
    } else {
        $_SESSION['error'] = "Erreur lors de l'enregistrement : " . $stmt->error;

        // Solo redirigir a register_form.php si NO estamos en el dashboard
        if (!isset($_POST['from_dashboard'])) {
            header("Location: register_form.php");
            exit();
        }
        // Si estamos en el dashboard, mostramos el error arriba del formulario
    }

    $stmt->close();
}
$mysqli->close();
