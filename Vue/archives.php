<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/style.css">
    <title>Archives</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="path-to-logo.png" alt="">
        </div>

        <nav>
            <ul>
                <li><a href="/Miniblog/Controller/index.php?action=home">Home</a></li>
                <li><a href="/Miniblog/Controller/index.php?action=showArchives">Archives</a></li>

                <!-- Afficher les liens supplémentaires si l'utilisateur est admin -->
                <?php if (isAdmin()): ?>
                    <li><a href="/Miniblog/Controller/index.php?action=preCreatePost">Ajouter un Billet</a></li>
                    <li><a href="/Miniblog/Controller/index.php?action=administration">Administration</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Profil et Déconnexion alignés à droite -->
        <div class="right-nav">
            <ul>
                <?php if (isLoggedIn()): ?>
                    <li><a href="/Miniblog/Controller/index.php?action=profile">Mon Profil</a></li>
                <?php else: ?>
                    <li><a href="/Miniblog/Controller/index.php?action=login">Connexion</a></li>
                    <li><a href="/Miniblog/Controller/index.php?action=register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <h1 class="archive-title">Liste des archives</h1>
    <div class="archive-container">
        <?php $allPosts = showAllPost(); ?>
        <?php foreach ($allPosts as $totalPosts): ?>
            <div class="post-item">
                <!-- Conteneur pour le titre et la photo -->
                <div class="post-header">
                    <h2 class="post-title"><?= htmlspecialchars($totalPosts['titre']) ?></h2>

                    <!-- Afficher la photo si elle existe -->
                    <?php if (!empty($totalPosts['photo_post'])): ?>
                        <div class="post-photo-container">
                            <img src="/Miniblog/uploads/<?= htmlspecialchars($totalPosts['photo_post']) ?>"
                                alt="Photo du billet" class="post-photo">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Contenu principal du billet -->
                <div class="post-body">
                    <p class="post-content"><?= htmlspecialchars($totalPosts['contenu']) ?></p>
                    <small class="post-date"><?= htmlspecialchars($totalPosts['date_post']) ?></small>
                    <a href="/Miniblog/Controller/index.php?action=blogDetails&id=<?= $totalPosts['id_billets'];?>">Voir plus</a>
                </div>

                <!-- Bouton de suppression pour l'admin -->
                <?php if (isAdmin()): ?>
                    <div class="post-actions">
                        <a href="/Miniblog/Controller/index.php?action=deletePost&id=<?= htmlspecialchars($totalPosts['id_billets']) ?>"
                            class="delete-button">Supprimer</a>
                    </div>
                <?php endif;?>
            </div>
        <?php endforeach;?>
    </div>
</body>

</html>