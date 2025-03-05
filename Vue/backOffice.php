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
                        onclick="showUpdateForm(<?php echo $allUser->getIdUser(); ?>)">Modifier</button>

                    <!-- Formulaire caché par défaut -->
                    <div id="user-update-form-<?php echo $allUser->getIdUser(); ?>" class="update-form"
                        style="display: none;">
                        <form action="index.php?action=updateUser&id=<?php echo $allUser->getIdUser(); ?>" method="POST"
                            class="update-form">
                            <h1 class="update-title">Mettre à Jour l'Utilisateur</h1>

                            <!-- Champ de mise à jour du login uniquement -->
                            <label for="user-login-<?php echo $allUser->getIdUser(); ?>" class="update-label">Login
                                :</label>
                            <input type="text" name="login" id="user-login-<?php echo $allUser->getIdUser(); ?>"
                                class="update-input" value="<?php echo htmlspecialchars($allUser->getLogin()); ?>"
                                required><br>

                            <button type="submit" class="update-button">Enregistrer les modifications</button>
                            <button type="button" class="update-cancel-button"
                                onclick="hideUpdateForm(<?php echo $allUser->getIdUser(); ?>)">Annuler</button>
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
                    <small class="post-date"><?= htmlspecialchars($allMessage->getPostedAt()->format('d/m/Y H:i')) ?> - ID:
                        <?= htmlspecialchars($allMessage->getId()) ?></small>

                    <?php if ($isAdmin): ?>
                        <a href="index.php?action=deletePost&id=<?= htmlspecialchars($allMessage->getId()) ?>"
                            class="delete-post">Supprimer</a>
                        <button class="edit-button" onclick="showPostUpdateForm(<?= $allMessage->getId(); ?>)">
                            Modifier le billet
                        </button>

                        <!-- Formulaire caché par défaut -->
                        <form action="index.php?action=updatePost&id=<?= $allMessage->getId(); ?>" method="POST"
                            class="update-form-post" id="update-form-post-<?= $allMessage->getId(); ?>" style="display: none;">
                            <label for="title-<?= $allMessage->getId(); ?>" class="update-label-post">Titre :</label>
                            <input type="text" name="title" id="titre-<?= $allMessage->getId(); ?>" class="update-input-post"
                                value="<?= htmlspecialchars($allMessage->getTitle()); ?>" placeholder="Nouveau titre"
                                required><br>

                            <label for="contenu-<?= $allMessage->getId(); ?>" class="update-label-post">Contenu :</label><br>
                            <textarea name="contenu" id="contenu-<?= $allMessage->getId(); ?>" class="update-textarea-post"
                                placeholder="Nouveau contenu"
                                required><?= htmlspecialchars($allMessage->getContenu()); ?></textarea><br>

                            <button type="submit" class="update-button-post">Enregistrer les modifications</button>
                            <button type="button" class="update-cancel-button-post"
                                onclick="hidePostUpdateForm(<?= $allMessage->getId(); ?>)">Annuler</button>
                        </form>
                    <?php endif ?>
                </div>
                <hr>
            <?php endforeach ?>
        </div>
        <div class="last-column">
            <h2 class="section-title">Liste des commentaires</h2>
            <?php $listComment = Commentaire::showAllComments($entityManager); ?>
            <?php foreach ($listComment as $listComments): ?>
                <div class="comments-item">
                    <p class="comment-title"><?= htmlspecialchars($listComments->getCommentaire()) ?></p>
                    <small><?= $listComments->getPostedAt()->format('Y-m-d H:i:s'); ?></small>
                    <a href="index.php?action=deleteCommentBo&id=<?= $listComments->getId(); ?>"
                        class="delete-post">Supprimer</a>

                    <form id="update-form-<?= $listComments->getId(); ?>"
                        action="index.php?action=updateComment&id=<?= $listComments->getId(); ?>" class="updateComments"
                        method="POST" style="display: none;">
                        <label for="contenu-<?= $listComments->getId(); ?>">Contenu</label>
                        <input type="text" name="commentaire" id="contenu-<?= $listComments->getId(); ?>" class="contenu"
                            value="<?= htmlspecialchars($listComments->getCommentaire()); ?>">
                        <input type="submit" value="Envoyer">
                        <button type="button" class="cancel-button"
                            onclick="hideCommentForm('<?= $listComments->getId(); ?>')">Annuler</button>
                    </form>
                    <button id="btn-comment-<?= $listComments->getId(); ?>" class="btnComment"
                        onclick="toggleCommentForm('<?= $listComments->getId(); ?>')">Modifier</button>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>