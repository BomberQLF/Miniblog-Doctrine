<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="index.js" defer></script>
    <link rel="stylesheet" href="./Style/style.css">
    <title>Miniblog</title>
</head>

<body>
    <section class="hero_section">
        <header>
            <?php if ($loggedUser): ?>
                <div class="logo">
                    <!-- <img src="/Miniblog/uploads/<?= $comments['photo_profile'] ?>" alt=""> -->
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
        <div class="hero_text_container">
            <div class="hero_title_container">
                <h1>Bee Blog</h1>
                <p>Ce mini-blog sur les abeilles est un projet universitaire réalisé dans le cadre de l’apprentissage du
                    développement web. Il permet d’explorer les bases du PHP et du MVC tout en mettant en avant un sujet
                    captivant.</p>
                <p style="margin-top: 2rem;"><em class="credit">Réalisé par Tom MURPHY</em></p>
            </div>
            <div class="hero_img_container">
                <img src="./assets/bee.png" alt="">
            </div>
        </div>
    </section>

    <main>
        <section class="recent_post">
            <div class="big_text">Mes <span><em class="big-text-hero">articles</em></span> récents sur les abeilles pour
                tout savoir sur les
                <span><em class="big-text-hero">ruches</em></span> et <span><em
                        class="big-text-hero">l’apiculture</em></span>
            </div>
            <div class="big_text">Découvertes autour du monde des abeilles et de la <span><em
                        class="big-text-hero">pollinisation</em></span>
            </div>
        </section>

        <div class="thanks"><em>Bonne lecture...</em></div>
        <section class="blog_section">
            <?php $messageRepository = $entityManager->getRepository(Message::class); ?>
            <?php $lastPosts = Message::getLastMessages($entityManager); ?>
            <div class="post-container">
                <?php foreach ($lastPosts as $post): ?>
                    <a class="linkBlog" href="index.php?action=blogDetails&id=<?= $post->getId(); ?>">
                        <div class="post-card">
                            <div class="post-content-container">
                                <h2 class="post-title"><?= htmlspecialchars($post->getTitle()); ?></h2>
                                <p class="post-excerpt"><?= htmlspecialchars($post->getContenu()); ?></p>
                                <div class="post-footer">
                                    <span class="post-read-more">Voir plus</span>
                                    <small class="post-date">
                                        Posté le : <?= htmlspecialchars($post->getPostedAt()->format('d/m/Y H:i')); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>

</html>