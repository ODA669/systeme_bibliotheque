<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travail Final Infraestructure web</title>
    <link rel="stylesheet" href="style/styles.css">

</head>
<body">
    <section class="login-container">
        <div class="form-container">
            <div class="form-header">
                <h2>Connexion</h2>
                <p>Connectez-vous à votre compte</p>
            </div>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur ou Email</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" required>
                </div>
                <!-- Radio buttons horizontales -->
                <div class="form-group radio-group">
                    <label>Type de compte :</label>
                    <div class="radio-options">
                        <label>
                            <input type="radio" name="tipo" value="client" checked>
                            Client
                        </label>
                        <label>
                            <input type="radio" name="tipo" value="employee">
                            Employé
                        </label>
                    </div>
                </div>
                <button type="submit" name="login" class="btn-submit">Se connecter</button>

            </form>
            <div class="form-footer">
                <p>Pas de compte ? <a href="register_form.php">Créer un compte</a></p>
            </div>
        </div>

    </section>
    <?php
    include_once "include/config.php";
    session_start();
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@..." integrity="sha384-..." crossorigin="anonymous"></script>
    </body>

</html>