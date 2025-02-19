<?php
session_start();
require_once('bootstrap.php');

$loggedUser = Utilisateur::isLoggedIn($entityManager);
$isAdmin = Utilisateur::isAdmin($entityManager);
$allMessages = Message::getAllMessages($entityManager);
// $allComments = Commentaire::showAllComments($entityManager);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'home':
        include('./Vue/miniblog.php');
        break;

    case 'inscription':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'inscription') {
            $login = $_POST['login'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';
            $confirmMotDePasse = $_POST['confirm_mot_de_passe'] ?? '';

            if (!empty($login) && !empty($motDePasse) && !empty($confirmMotDePasse)) {

                if ($motDePasse === $confirmMotDePasse) {
                    $user = Utilisateur::createUser($entityManager, $login, $motDePasse);

                    include('./Vue/login.php');
                    exit();
                }
            } else {
                $error = "Les mots de passe ne correspondent pas.";
            }
        } else {
            $error = "Tous les champs doivent être remplis.";
        }
        break;


    case 'showArchives':
        include('./Vue/archives.php');
        break;

    case 'preCreatePost':
        if ($isAdmin) {
            include('./Vue/createPost.php');
        } else {
            echo "Accès refusé.";
        }
        break;

    case 'administration':
        if ($isAdmin) {
            include('./Vue/backOffice.php');
        } else {
            echo "Accès refusé.";
        }
        break;

    case 'login':
        include('./Vue/login.php');
        break;

    case 'register':
        include('./Vue/inscription.php');
        break;

    case 'connect':
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $login = $_POST['login'];
            $pwd = $_POST['password'];

            $user = Utilisateur::findByLogin($entityManager, $login);

            if ($user && $user->verifyPwd($pwd)) {
                $_SESSION['user_id'] = $user->getIdUser();
                header("Location: index.php?action=home");
                exit();
            } else {
                header("Location: index.php?action=login&error=invalid");
                echo 'Identifiants invalides';
                exit();
            }
        }
        break;

    case 'profile':
        $loggedUser ? include('./Vue/gestionProfile.php') : header("Location: index.php?action=login");
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?action=home");
        exit();

    case 'upload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo_profile'])) {
            $file = $_FILES['photo_profile'];

            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = mime_content_type($file['tmp_name']);

                if (!in_array($fileType, $allowedTypes)) {
                    echo "Format d'image non supporté.";
                    exit();
                }

                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $extension;
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $loggedUser->setPhotoProfile($fileName);
                    $entityManager->persist($loggedUser);
                    $entityManager->flush();

                    header("Location: index.php?action=profile");
                    exit();
                } else {
                    echo "Erreur lors de l'upload du fichier.";
                }
            } else {
                echo "Erreur lors de l'upload : Code " . $file['error'];
            }
        }
        exit;

    case 'deletePost':
        if ($isAdmin) {
            $id = $_GET['id'];
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                Message::deleteMessage($entityManager, $id);
                header("Location: index.php?action=showArchives");
                exit();
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'createPost':
        if ($isAdmin) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_POST['title']) && !empty($_POST['contenu']) && !empty($_POST['user_id'])) {
                    $title = $_POST['title'];
                    $contenu = $_POST['contenu'];
                    $user_id = $_POST['user_id'];

                    Message::createMessage($entityManager, $title, $contenu, $user_id);

                    header("Location: index.php?action=showArchives");
                    exit();
                } else {
                    echo "Erreur : Tous les champs sont requis.";
                }
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'blogDetails':
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $message = Message::showMessageById($entityManager, $id);

            if ($message) {
                include('./Vue/blogDetails.php');
            } else {
                echo "Message non trouvé.";
            }
        } else {
            echo "ID invalide.";
        }
        break;

    case 'postComment':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu'])) {
            $messageId = isset($_POST['message_id']) ? (int) $_POST['message_id'] : null;
            $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : null;
            $contenu = trim($_POST['contenu']);

            if ($messageId && $userId && !empty($contenu)) {
                Commentaire::postComment($entityManager, $messageId, $userId, $contenu);
                header("Location: index.php?action=blogDetails&id=$messageId");
                exit;
            } else {
                echo "Erreur : tous les champs sont requis.";
            }
        }
        break;

    case 'deleteComment':
        if ($isAdmin) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($_GET['id'])) {
                    $idComment = $_GET['id'];

                    if ($idComment) {
                        Commentaire::deleteCommentById($entityManager, $idComment);

                        include('./Vue/archives.php');
                    } else {
                        echo "ID du commentaire invalide.";
                    }
                } else {
                    echo "L'ID n'exite pas.";
                }
            }
        }
        break;

    case 'deleteCommentBo':
        if ($isAdmin) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($_GET['id'])) {
                    $idComment = $_GET['id'];
                    if ($idComment) {
                        Commentaire::deleteCommentById($entityManager, $idComment);
                        include('./Vue/backOffice.php');
                    } else {
                        include('./Vue/miniblog.php');
                        echo "ID du commentaire invalide.";
                    }
                } else {
                    echo "L'ID n'exite pas.";
                }
            }
        }
        break;

    case 'deleteUser':
        if ($isAdmin) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($_GET['id'])) {
                    $idUser = $_GET['id'];
                    Utilisateur::deleteUser($entityManager, $idUser);
                    include('./Vue/backOffice.php');
                    exit();
                } else {
                    echo "ID de l'utilisateur manquant.";
                }
            }
        }
        break;

    case 'updateUser':
        if ($isAdmin) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_GET['id']) && !empty($_POST['login'])) {
                    $idUser = $_GET['id'];
                    $login = $_POST['login'];

                    $user = Utilisateur::updateUser($entityManager, $idUser, $login);
                    include('./Vue/backOffice.php');
                }
            } else {
                echo "Le login est nécessaire.";
            }
        }
        break;

    case 'updatePost':
        if ($isAdmin) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_GET['id']) && !empty($_POST['contenu'])) {
                    $postId = $_GET['id'];
                    $contenu = $_POST['contenu'];
                    $title = $_POST['title'];

                    $post = Message::updatePost($entityManager, $postId, $title, $contenu);

                    if ($post) {
                        include('./Vue/backOffice.php');
                    } else {
                        echo "Erreur : Le post n'existe pas.";
                    }
                } else {
                    echo "Erreur : Le contenu du post est requis.";
                }
            }
        }
        break;

    case 'updateComment':
        if ($isAdmin) {
            if ($_SERVER['REQUEST_METHOD'] === "POST") {
                if (!isset($_GET['id']) || !isset($_POST['commentaire'])) {
                    die("Erreur : Paramètres manquants.");
                }

                $commentId = (int) $_GET['id'];
                $commentaire = trim($_POST['commentaire']);

                if ($commentaire === '') {
                    die("Erreur : Le commentaire est vide.");
                }

                $comment = Commentaire::updateComment($entityManager, $commentId, $commentaire);

                if ($comment) {
                    include('./Vue/backOffice.php');
                } else {
                    die("Erreur : Le commentaire n'existe pas.");
                }
            }
        }
        break;

    default:
        include('./Vue/miniblog.php');
        break;
}
