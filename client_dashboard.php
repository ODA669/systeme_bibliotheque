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
                <li>
                    <span>Le Petit Prince</span>
                    <button class="request">Demander emprunt</button>
                </li>
                <li>
                    <span>Les Misérables</span>
                    <button class="request">Demander emprunt</button>
                </li>
                <li>
                    <span>Harry Potter</span>
                    <button class="request disabled" title="Maximum d'emprunts atteint">Demander emprunt</button>
                </li>
            </ul>
        </section>

        <!-- Faire une demande d’emprunt pour un livre -->
        <section>
            <h2>Faire une demande d’emprunt</h2>
            <ul>
                <li>
                    <span>Demande pour "Le Petit Prince"</span>
                    <button class="request">Envoyer la demande</button>
                </li>
                <li>
                    <span>Demande pour "Les Misérables"</span>
                    <button class="request">Envoyer la demande</button>
                </li>
            </ul>
        </section>

        <!-- Mes emprunts en cours -->
        <section>
            <h2>Mes emprunts en cours (max 2)</h2>
            <ul>
                <li>
                    <span>Le Petit Prince - emprunté le 20/08/2025</span>
                    <button class="borrowed" disabled>En cours</button>
                </li>
                <li>
                    <span>Les Misérables - emprunté le 22/08/2025</span>
                    <button class="borrowed" disabled>En cours</button>
                </li>
            </ul>
        </section>
    </main>
</body>

</html>