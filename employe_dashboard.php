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
        <h1>Dashboard Employé - Bibliothèque</h1>
        <button class="logout-btn" onclick="alert('Déconnexion effectuée!')">Déconnexion</button>
    </header>

    <main>
        <!-- Gestion d'inventaire -->
        <section>
            <h2>Gérer l’inventaire des livres</h2>
            <ul>
                <li>
                    <span>Le Petit Prince (5 disponibles)</span>
                    <div>
                        <button class="add">Ajouter</button>
                        <button class="remove">Retirer</button>
                    </div>
                </li>
                <li>
                    <span>Les Misérables (2 disponibles)</span>
                    <div>
                        <button class="add">Ajouter</button>
                        <button class="remove">Retirer</button>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Emprunts en cours -->
        <section>
            <h2>Emprunts en cours</h2>
            <ul>
                <li>
                    <span>Alice - Le Petit Prince (En cours)</span>
                    <button class="confirm">Confirmer retour</button>
                </li>
                <li>
                    <span>Bob - Les Misérables (En cours)</span>
                    <button class="confirm">Confirmer retour</button>
                </li>
            </ul>
        </section>

        <!-- Demandes en attente -->
        <section>
            <h2>Demandes d’emprunt en attente</h2>
            <ul>
                <li>
                    <span>Charlie demande Le Petit Prince</span>
                    <div>
                        <button class="accept">Accepter</button>
                        <button class="reject">Refuser</button>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Création de compte client -->
        <section>
            <h2>Créer un nouveau compte client</h2>
            <form>
                <input type="text" placeholder="Nom" required>
                <input type="email" placeholder="Email" required>
                <input type="password" placeholder="Mot de passe" required>
                <button type="submit">Créer le compte</button>
            </form>
        </section>
    </main>
</body>

</html>