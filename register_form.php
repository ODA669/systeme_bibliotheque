<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="style/styles.css">
</head>

<body>
    <div class="form-container">
        <div class="form-header">
            <h2>Créer un compte</h2>
            <p>Remplissez vos informations pour vous inscrire</p>
        </div>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom">
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom">
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse">
            </div>

            <!-- Select para tipo de usuario -->
            <div class="form-group">
                <label for="tipo">Type de compte</label>
                <select name="tipo" id="tipo" required>
                    <option value="client" selected>Client</option>
                    <option value="employee">Employé</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>
        <div class="form-footer">
            <p>Déjà un compte ? <a href="index.php">Se connecter</a></p>
        </div>
    </div>



</body>

</html>