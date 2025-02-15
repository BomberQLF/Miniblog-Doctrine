<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Style/style.css">
    <script src="index.js" defer></script>
    <title>Blog Details</title>
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

    <main>
        <div class="introduction">
            <?php if ($loggedUser): ?>
                <p>Découvrez les détails de l'article ci-dessous. Explorez le contenu complet et laissez vos commentaires
                    pour une discussion plus approfondie.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['id'])): ?>
            <?php
            if ($message): ?>
                <div class="post-detail">
                    <h2 class="h2_post"><?php echo htmlspecialchars($message->getTitle()); ?></h2>
                    <p><?php echo htmlspecialchars($message->getContenu()); ?></p>
                    <div class="post-meta">
                        <small class="small_post_date"><em>Date de publication :
                                <?php echo htmlspecialchars($message->getPostedAt()?->format('Y-m-d H:i:s')); ?></em></small>
                    </div>
                </div>
                <div class="comment_part">
                    <?php if ($loggedUser): ?>
                        <h2>Commentaires</h2>
                        <form action="index.php?action=postComment" method="POST">
                            <input type="hidden" name="message_id" value="<?= $_GET['id'] ?>">
                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">

                            <div>
                                <label for="commentContent">Votre commentaire :</label><br>
                                <textarea name="contenu" id="commentContent" rows="5" placeholder="Écrivez votre commentaire ici..."
                                    required></textarea><br>
                            </div>
                            <div>
                                <button type="submit">Poster le commentaire</button>
                                <?php if (isset($echecAjout)) {
                                    echo $echecAjout;
                                } ?>
                            </div>
                        </form>
                    <?php endif; ?>

                    <?php $comments = Commentaire::getCommentsByMessageId($entityManager, $_GET['id']); ?>
                    <?php if (!empty($comments)): ?>
                        <div class="comments-container">
                            <button id="toggle-comments-btn">Voir les commentaires</button>

                            <div id="comments-section" class="comments-section" style="display: none;">
                                <?php foreach ($comments as $comment): ?>
                                    <div class="comment">
                                        <!-- Affichage de la photo de profil -->
                                        <?php if (!empty($comment->getUser()->getPhotoProfile())): ?>
                                            <img src="./uploads/<?php echo htmlspecialchars($comment->getUser()->getPhotoProfile()); ?>"
                                                alt="Photo de profil de <?php echo htmlspecialchars($comment->getUser()->getLogin()); ?>"
                                                class="profile-photo">
                                        <?php else: ?>
                                            <img src="./uploads/photo_default.png" alt="Photo de profil par défaut" class="profile-photo">
                                        <?php endif; ?>

                                        <!-- Affichage du commentaire -->
                                        <p><?php echo htmlspecialchars($comment->getCommentaire()); ?></p>

                                        <!-- Affichage de l'auteur et de la date -->
                                        <small>
                                            Posté par : <?php echo htmlspecialchars($comment->getUser()->getLogin()); ?>
                                            le <?php echo $comment->getPostedAt()->format('d/m/Y à H:i'); ?>
                                        </small>

                                        <!-- Bouton de suppression si admin -->
                                        <?php if ($isAdmin): ?>
                                            <a href="index.php?action=deleteComment&id=<?php echo $comment->getId(); ?>">Supprimer</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>Aucun commentaire pour cet article.</p>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <p>Aucun post trouvé avec cet identifiant.</p>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>

</html>