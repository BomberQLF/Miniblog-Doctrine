<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Style/style.css">
    <title>Archives</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="path-to-logo.png" alt="">
        </div>

        <nav>
            <ul>
                <li><a href="index.php?action=home">Home</a></li>
                <li><a href="index.php?action=showArchives">Archives</a></li>

                <!-- Afficher les liens supplémentaires si l'utilisateur est admin -->
                <?php if ($isAdmin): ?>
                    <li><a href="index.php?action=preCreatePost">Ajouter un Billet</a></li>
                    <li><a href="index.php?action=administration">Administration</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Profil et Déconnexion alignés à droite -->
        <div class="right-nav">
            <ul>
                <?php if ($loggedUser): ?>
                    <li><a href="index.php?action=profile">Mon Profil</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=login">Connexion</a></li>
                    <li><a href="index.php?action=register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <h1 class="archive-title">Liste des archives</h1>
    <div class="archive-container">
        <?php foreach ($allMessages as $messages): ?>
            <div class="post-item">
                <!-- Conteneur pour le titre et la photo -->
                <div class="post-header">
                    <h2 class="post-title"><?= htmlspecialchars($messages->getTitle()) ?></h2> 
                </div>

                <!-- Contenu principal du billet -->
                <div class="post-body">
                    <p class="post-content"><?= htmlspecialchars($messages->getContenu()) ?></p>
                    <small class="post-date"><?= htmlspecialchars($messages->getPostedAt()?->format('Y-m-d H:i:s')) ?></small>
                    <a href="index.php?action=blogDetails&id=<?= $messages->getId();?>">Voir plus</a>
                </div>

                <!-- Bouton de suppression pour l'admin -->
                <?php if ($isAdmin): ?>
                    <div class="post-actions">
                        <a href="index.php?action=deletePost&id=<?= $messages->getId()?>"
                            class="delete-button">Supprimer</a>
                    </div>
                <?php endif;?>
            </div>
        <?php endforeach;?>
    </div>
</body>

</html>