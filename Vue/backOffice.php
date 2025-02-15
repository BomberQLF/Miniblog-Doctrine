<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Style/style.css">
    <script src="index.js" defer></script>
    <title>Back Office</title>
</head>

<body>
    <header>
        <?php if ($loggedUser): ?>
            <div class="logo">
                <img src="path-to-logo.png" alt="">
            </div>
        <?php endif; ?>
        <nav>
            <ul>
                <li><a href="index.php?action=home">Home</a></li>
                <li><a href="index.php?action=showArchives">Archives</a></li>

                <?php if ($isAdmin): ?>
                    <li><a href="index.php?action=preCreatePost">Ajouter un Billet</a></li>
                    <li><a href="index.php?action=administration">Administration</a></li>
                <?php endif; ?>
            </ul>
        </nav>

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
    <h1 class="backoffice-title" style="margin-bottom: 4rem;">BACKOFFICE</h1>
    <hr>
    <div class="backoffice-container">
        <div class="left-column">
            <h2 class="section-title">Liste des utilisateurs :</h2>
            <?php $allUsers = Utilisateur::showAllUsers($entityManager); ?>
            <?php foreach ($allUsers as $allUser): ?>
                <div class="user-item">
                    <p class="user-info">ID: <?php echo $allUser->getIdUser(); ?></p>
                    <p class="user-info">Login: <?php echo htmlspecialchars($allUser->getLogin()); ?></p>
                    <a href="index.php?action=deleteUser&id=<?php echo $allUser->getIdUser(); ?>"
                        class="delete-user">Supprimer l'utilisateur</a>
                    <button class="edit-button"
                        onclick="showUpdateForm(<?php echo $allUser['id_utilisateurs']; ?>)">Modifier</button>

                    <!-- Formulaire caché par défaut -->
                    <div id="update-form-<?php echo $allUser['id_utilisateurs']; ?>" class="update-form"
                        style="display: none;">
                        <form action="index.php?action=updateUser&id=<?php echo $allUser['id_utilisateurs']; ?>"
                            method="POST" class="update-form">
                            <h1 class="update-title">Mettre à Jour l'Utilisateur</h1>

                            <!-- Champ de mise à jour du login uniquement -->
                            <label for="login-<?php echo $allUser['id_utilisateurs']; ?>" class="update-label">Login
                                :</label>
                            <input type="text" name="login" id="login-<?php echo $allUser['id_utilisateurs']; ?>"
                                class="update-input" value="<?php echo htmlspecialchars($allUser['login']); ?>"
                                required><br>

                            <button type="submit" class="update-button">Enregistrer les modifications</button>
                            <button type="button" class="update-cancel-button"
                                onclick="hideUpdateForm(<?php echo $allUser['id_utilisateurs']; ?>)">Annuler</button>
                        </form>
                    </div>
                </div>
                <hr>
            <?php endforeach ?>
        </div>

        <div class="right-column">
            <h2 class="section-title">Liste des billets :</h2>
            <?php foreach ($allMessages as $allMessage): ?>
                <div class="post-item">
                    <h3 class="post-title"><?= htmlspecialchars($allMessage->getTitle()) ?></h3>
                    <p class="post-content"><?= htmlspecialchars($allMessage->getContenu()) ?></p>
                    <small class="post-date"><?= htmlspecialchars($allMessage->getPostedAt()) ?> - ID:
                        <?= htmlspecialchars($totalPosts['id_billets']) ?></small>

                    <?php if ($isAdmin): ?>
                        <a href="index.php?action=deletePost&id=<?= htmlspecialchars($totalPosts['id_billets']) ?>"
                            class="delete-post">Supprimer</a>
                        <button class="edit-button" onclick="showPostUpdateForm(<?= $totalPosts['id_billets']; ?>)">
                            Modifier le billet
                        </button>
                        <!-- Formulaire caché par défaut -->
                        <form action="index.php?action=updatePost&id=<?= $totalPosts['id_billets']; ?>" method="POST"
                            class="update-form-post" id="update-form-post-<?= $totalPosts['id_billets']; ?>"
                            style="display: none;">
                            <label for="titre-<?= $totalPosts['id_billets']; ?>" class="update-label-post">Titre :</label>
                            <input type="text" name="titre" id="titre-<?= $totalPosts['id_billets']; ?>"
                                class="update-input-post" value="<?= htmlspecialchars($totalPosts['titre']); ?>"
                                placeholder="Nouveau titre" required><br>

                            <label for="contenu-<?= $totalPosts['id_billets']; ?>" class="update-label-post">Contenu
                                :</label><br>
                            <textarea name="contenu" id="contenu-<?= $totalPosts['id_billets']; ?>" class="update-textarea-post"
                                placeholder="Nouveau contenu"
                                required><?= htmlspecialchars($totalPosts['contenu']); ?></textarea><br>

                            <button type="submit" class="update-button-post">Enregistrer les modifications</button>
                            <button type="button" class="update-cancel-button-post"
                                onclick="hidePostUpdateForm(<?= $totalPosts['id_billets']; ?>)">Annuler</button>
                        </form>
                    <?php endif ?>
                </div>
                <hr>
            <?php endforeach ?>
        </div>
        <div class="last-column">
            <h2 class="section-title">Liste des commentaires</h2>
            <?php $listComment = showAllComment(); ?>
            <?php foreach ($listComment as $listComments): ?>
                <div class="comments-item">
                    <p class="comment-title"><?= $listComments['contenu'] ?></p>
                    <small><?= $listComments['date_post']; ?></small>
                    <a href="index.php?action=deleteComment&id=<?= $listComments['id_commentaires']; ?>"
                        class="delete-post">Supprimer</a>

                    <form id="update-form-<?= $listComments['id_commentaires']; ?>"
                        action="index.php?action=updateComment&id=<?= $listComments['id_commentaires']; ?>"
                        class="updateComments" method="POST" style="display: none;">
                        <label for="contenu-<?= $listComments['id_commentaires']; ?>">Contenu</label>
                        <input type="text" name="contenu" id="contenu-<?= $listComments['id_commentaires']; ?>"
                            class="contenu" value="<?= $listComments['contenu']; ?>">
                        <input type="submit" value="Envoyer">
                        <button type="button" class="cancel-button"
                            onclick="hideCommentForm('<?= $listComments['id_commentaires']; ?>')">Annuler</button>
                    </form>
                    <button id="btn-comment-<?= $listComments['id_commentaires']; ?>" class="btnComment"
                        onclick="toggleCommentForm('<?= $listComments['id_commentaires']; ?>')">Modifier</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>