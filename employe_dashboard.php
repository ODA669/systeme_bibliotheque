<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Employé - Bibliothèque</title>
    <link rel="stylesheet" href="style/style_dashboard.css">
</head>

<body>

    <header>
        <div class="dashboard-header">
            <h1>Dashboard Employé - Bibliothèque</h1>

            <form method="post" action="logout.php">
                <button type="submit">Déconnexion</button>
            </form>
        </div>
    </header>

    <main>
        <!-- Gestion d'inventaire -->
        <section>
            <div class="livres_header">
                <div>
                    <h2>Gérer l’inventaire des livres</h2>
                </div>
                <div>
                    <a href="form_inventaire.php">
                        <button class="add">Ajouter</button>
                    </a>
                </div>
            </div>
            <ul>
                <?php
                session_start();
                include_once "include/config.php"; // $host, $username, $password, $database

                // Conexión
                $mysqli = new mysqli($host, $username, $password, $database);

                if ($mysqli->connect_errno) {
                    echo "Échec de la connexion à la base de données MySql: " . $mysqli->connect_error;
                    exit();
                } else {
                    //echo "<p>La connexion à bien fonctionnée!</p>";
                }

                // PROCESAR BORRADO
                if (isset($_POST['delete']) && isset($_POST['id'])) {
                    $id = intval($_POST['id']);
                    $sql_delete = "DELETE FROM livres WHERE id = $id"; // la misma tabla que usas en SELECT
                    if ($mysqli->query($sql_delete)) {
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    } else {
                        echo "Error al borrar: " . $mysqli->error;
                    }
                }


                // Consulta a la tabla libros
                $sql = "SELECT * FROM livres";
                $resultat = $mysqli->query($sql);

                if ($resultat->num_rows > 0) {
                    while ($fila = $resultat->fetch_assoc()) {
                        echo "<li>";
                        echo "<span>" . htmlspecialchars($fila['titre']) . " disponible)</span>";
                        echo "<div>";
                        // Botón para borrar
                        echo "<form method='post' style='display:inline;'>";
                        echo "<input type='hidden' name='id' value='" . intval($fila['id']) . "'>";
                        echo "<button type='submit' name='delete' class='remove'>Retirer</button>";
                        echo "</form>";
                        // echo "<button class='remove'>Retirer</button>";
                        echo "</div>";
                        echo "</li>";
                    }
                } else {
                    echo "<li>No hay libros disponibles.</li>";
                }

                // Cerrar conexión
                // $mysqli->close();
                // 
                ?>
            </ul>

        </section>

        <!-- Emprunts en cours -->
        <section>
            <h2>Emprunts en cours</h2>
            <ul>
                <?php


                if ($mysqli->connect_errno) {
                    echo "Échec de la connexion à la base de données MySql: " . $mysqli->connect_error;
                    exit();
                } else {
                    //echo "<p>La connexion à bien fonctionnée!</p>";
                }


                // Procesar devolución
                if (isset($_POST['confirmer_retour']) && isset($_POST['emprunt_id'])) {
                    $emprunt_id = intval($_POST['emprunt_id']);

                    // Fecha actual
                    $dateRetour = date("Y-m-d");

                    // 1. Actualizar la tabla emprunts (poner la fecha de retour)
                    $sql_update = "UPDATE emprunts SET dateRetour = '$dateRetour' WHERE id = $emprunt_id";
                    if ($mysqli->query($sql_update)) {
                        // 2. (Opcional) Marcar el libro como disponible
                        $mysqli->query("UPDATE livres SET disponible = 1 WHERE id = (SELECT fk_livre FROM emprunts WHERE id = $emprunt_id)");

                        header("Location: " . $_SERVER['PHP_SELF']); // refrescar la página
                        exit;
                    } else {
                        echo "Erreur lors de la confirmation du retour : " . $mysqli->error;
                    }
                }
                // Consulta a la tabla libros
                $sql = "SELECT e.id, e.dateEmprunt, e.dateRetour, e.dateMaxRetour, e.fk_client, l.titre 
                        FROM emprunts e
                        INNER JOIN livres l ON e.fk_livre = l.id
                        WHERE e.dateRetour IS NULL";

                $resultat = $mysqli->query($sql);

                if ($resultat->num_rows > 0) {
                    while ($fila = $resultat->fetch_assoc()) {
                        echo "<li>";
                        echo "<span>" . htmlspecialchars($fila['titre']) . " (emprunt en cours)</span>";
                        echo "<div>";
                        echo "<form method='post' style='display:inline;'>";
                        echo "<input type='hidden' name='emprunt_id' value='" . intval($fila['id']) . "'>";
                        echo "<button type='submit' name='confirmer_retour' class='remove'>Confirmer retour</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</li>";
                    }
                } else {
                    echo "<li>NN'il y a de restours prevus</li>";
                }


                ?>
            </ul>

        </section>

        <!-- Demandes en attente -->
        <section>
            <h2>Demandes d’emprunt en attente o Accepter ou refuser une demande d’emprunt en attente.</h2>
            <ul>
                <?php
                // Suponiendo que ya tienes $mysqli conectado

                if (isset($_POST['accepter']) && isset($_POST['demande_id'])) {
                    $id = intval($_POST['demande_id']);
                    $dateEmprunt = date("Y-m-d");

                    // Marcar la solicitud como aceptada (añadir fecha y aceptar)
                    $sql_accept = "UPDATE emprunts SET accepte = 1, dateEmprunt = '$dateEmprunt', dateMaxRetour = DATE_ADD('$dateEmprunt', INTERVAL 15 DAY) WHERE id = $id";
                    $mysqli->query($sql_accept);
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                }

                if (isset($_POST['refuser']) && isset($_POST['demande_id'])) {
                    $id = intval($_POST['demande_id']);
                    // Marcar la solicitud como rechazada
                    $sql_reject = "UPDATE emprunts SET accepte = 0 WHERE id = $id";
                    $mysqli->query($sql_reject);
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                }


                // Traer solicitudes pendientes
                $sql = "SELECT e.id, l.titre, c.prenom, c.nom
                        FROM emprunts e
                        INNER JOIN livres l ON e.fk_livre = l.id
                        INNER JOIN client c ON e.fk_client = c.client_id
                        WHERE e.accepte IS NULL";

                $result = $mysqli->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($fila = $result->fetch_assoc()) {

                        $prenom = $fila['prenom'];
                        $nom = $fila['nom'];
                        $titre = $fila['titre'];
                        echo "<li>";
                        // echo "<span>" . htmlspecialchars($fila['prenom']) . " " . htmlspecialchars($fila['nom']) . " demande " . htmlspecialchars($fila['titre']) . "</span>";
                        echo "<span>$prenom $nom demande $titre</span>";
                        echo "<div>";
                        echo "<form method='post' style='display:inline;'>";
                        echo "<input type='hidden' name='demande_id' value='" . intval($fila['id']) . "'>";
                        echo "<button type='submit' name='accepter' class='accept'>Accepter</button>";
                        echo "</form>";

                        // Formulario para rechazar
                        echo "<form method='post' style='display:inline;'>";
                        echo "<input type='hidden' name='demande_id' value='" . intval($fila['id']) . "'>";
                        echo "<button type='submit' name='refuser' class='reject'>Refuser</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</li>";
                    }
                } else {
                    echo "<li>Aucune demande en attente</li>";
                }
                // Cerrar conexión
                $mysqli->close();
                ?>
            </ul>
        </section>

        <!-- Création de compte client -->
        <section>
            <h2>Créer un nouveau compte client</h2>
            <br />
            <?php
            // Mostrar mensajes de sesión
            if (isset($_SESSION['success'])) {
                echo "<div class='alert success'>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "<div class='alert error'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
            ?>

            <form method="post" action="register.php">
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                <input type="text" name="adresse" placeholder="Adresse">
                <input type="hidden" name="tipo" value="client">
                <input type="hidden" name="from_dashboard" value="1">
                <button type="submit">Créer le compte</button>
            </form>
        </section>
    </main>
</body>

</html>