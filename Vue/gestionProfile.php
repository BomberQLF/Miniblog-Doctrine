<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Style/style.css">
    <title>Profile</title>
</head>

<body class="bodyProfile">
    <header>
        <?php
        if (!$loggedUser) {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $loggedUser->getIdUser();
        $login = $loggedUser->getLogin();
        ?>
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
    <section class="profile-container">
        <div class="container-profile">
            <h1 class="profile-title">Informations Utilisateur</h1>

            <!-- PHOTO DE PROFIL -->

            <div class="profile-info">
                <p class="profile-detail"><span class="detail-label">ID :</span> <?php echo $id ?>
                <p class="profile-detail"><span class="detail-label">Login :</span> <?php echo $login?></p>
                </p>
            </div>

            <h2 class="profile-subtitle">Modifier la photo de profil</h2>
            <form action="index.php?action=upload" method="POST" enctype="multipart/form-data"
                class="profile-form">
                <input type="file" name="photo_profile" accept="image/*" class="file-input" required><br>
                <button type="submit" class="upload-button">Ajouter la photo</button>
                <?php if (isset($error)) {
                    echo $error;
                }
                ?>
            </form>
            <a class="logout-link" href="index.php?action=logout">Se Déconnecter</a>
        </div>
        <div class="profile_img">
            <img src="uploads/<?php echo htmlspecialchars(urldecode($loggedUser->getPhotoProfile())); ?>" alt="Photo de profil">        
        </div>
    </section>
</body>

</html>