<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client - Bibliothèque</title>
    <link rel="stylesheet" href="style/style_dashboard.css">

</head>

<body>
    <header>

        <div class="dashboard-header">
            <h1>Dashboard Client - Bibliothèque</h1>

            <form method="post" action="logout.php">
                <button type="submit">Déconnexion</button>
            </form>
        </div>
    </header>

    <main>
        <!-- Liste des livres disponibles -->
        <section>
            <h2>Livres disponibles</h2>
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
                        // echo "<input type='hidden' name='id' value='" . intval($fila['id']) . "'>";
                        // echo "<button type='submit' name='delete' class='remove'>Retirer</button>";
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
                ?>

            </ul>
        </section>

        <!-- Faire une demande d’emprunt pour un livre -->
        <section>
            <h2>Faire une demande d’emprunt</h2>
            <ul>
                <?php
                // --- 1) Procesar un emprunt si hay POST ---
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fk_livre'], $_POST['fk_client'])) {
                    $fk_livre = intval($_POST['fk_livre']);
                    $fk_client = intval($_POST['fk_client']);

                    // 1) comprobar libro existe
                    $stmt = $conn->prepare("SELECT id, titre FROM livres WHERE id = ?");
                    $stmt->bind_param("i", $fk_livre);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($res->num_rows === 0) {
                        echo "<li>❌ Error: libro no existe.</li>";
                    } else {
                        $stmt->close();

                        // 2) comprobar cliente existe y activo
                        $stmt = $conn->prepare("SELECT client_id FROM client WHERE client_id = ? AND actif = 1");
                        $stmt->bind_param("i", $fk_client);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows === 0) {
                            echo "<li>❌ Error: cliente no existe o no está activo.</li>";
                        } else {
                            $stmt->close();

                            // 3) comprobar si el libro ya está prestado
                            $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM emprunts WHERE fk_livre = ? AND accepte = 1 AND dateRetour IS NULL");
                            $stmt->bind_param("i", $fk_livre);
                            $stmt->execute();
                            $row = $stmt->get_result()->fetch_assoc();
                            $stmt->close();

                            if ($row['cnt'] > 0) {
                                echo "<li>❌ Lo siento: el libro ya está prestado actualmente.</li>";
                            } else {
                                // 4) insertar la demande
                                $dateEmprunt = date('Y-m-d');
                                $dateMaxRetour = date('Y-m-d', strtotime('+15 days'));

                                $stmt = $conn->prepare("INSERT INTO emprunts (fk_livre, dateEmprunt, dateMaxRetour, fk_client, accepte) VALUES (?, ?, ?, ?, 0)");
                                $stmt->bind_param("issi", $fk_livre, $dateEmprunt, $dateMaxRetour, $fk_client);

                                if ($stmt->execute()) {
                                    echo "<li>✅ Demande d'emprunt registrada (pendiente de aceptación).</li>";
                                } else {
                                    echo "<li>❌ Error al insertar: " . $stmt->error . "</li>";
                                }
                                $stmt->close();
                            }
                        }
                    }
                }

                // --- 2) Mostrar lista de libros ---
                $resultat = $conn->query("SELECT id, titre, auteur FROM livres");

                if ($resultat && $resultat->num_rows > 0) {
                    while ($fila = $resultat->fetch_assoc()) {
                        echo "<li>";
                        echo "<span>" . $fila['titre'] . " - " . $fila['auteur'] . "</span>";

                        // Formulario para pedir un emprunt
                        echo "<form method='post' action='client_dashboard.php' style='display:inline;'>";
                        echo "<input type='hidden' name='fk_livre' value='" . intval($fila['id']) . "'>";
                        echo "<input type='hidden' name='fk_client' value='1'>"; // aquí debes poner el ID del cliente logueado
                        echo "<button type='submit'>Demander emprunt</button>";
                        echo "</form>";

                        echo "</li>";
                    }
                } else {
                    echo "<li>Aucun livre trouvé</li>";
                }

                ?>
            </ul>
        </section>

        <!-- Mes emprunts en cours -->
        <section>
            <h2>Mes emprunts en cours (max 2)</h2>
            <ul>
                <?php
                $sql = "SELECT e.id, l.titre, e.dateEmprunt
                FROM emprunts e
                JOIN livres l ON e.fk_livre = l.id
                WHERE e.fk_client = ? 
                  AND e.accepte = 1 
                  AND e.dateRetour IS NULL
                ORDER BY e.dateEmprunt DESC
                LIMIT 2";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $fk_client);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($fila = $result->fetch_assoc()) {
                        echo "<li>";
                        echo "<span>" . htmlspecialchars($fila['titre']) . " - emprunté le " . htmlspecialchars($fila['dateEmprunt']) . "</span>";
                        echo " <button class='borrowed' disabled>En cours</button>";
                        echo "</li>";
                    }
                } else {
                    echo "<li>Aucun emprunt en cours</li>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </ul>
        </section>
    </main>
</body>

</html>