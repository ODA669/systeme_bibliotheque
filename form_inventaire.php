<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
    <link rel="stylesheet" href="style/styles.css">
</head>

<body>
    <form action="ajouter_livre.php" method="post">
        <h2>Ajouter un nouveau livre</h2>

        <label for="titre">Titre :</label>
        <input type="text" name="titre" required>

        <label for="auteur">Auteur :</label>
        <input type="text" name="auteur" required>

        <label for="datePublication">Date de publication :</label>
        <input type="date" name="datePublication" required>

        <label for="nombrePages">Nombre de pages :</label>
        <input type="number" name="nombrePages" min="1" required>

        <input type="submit" value="Ajouter">
    </form>
</body>

</html>