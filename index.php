<?php
session_start();
require_once('bootstrap.php');

$loggedUser = Utilisateur::isLoggedIn($entityManager);
$isAdmin = Utilisateur::isAdmin($entityManager);
$allMessages = Message::getAllMessages($entityManager);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'home':
        include('./Vue/miniblog.php');
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
            include('./Vue/admin.php');
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
        break;

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

    default:
        include('./Vue/miniblog.php');
        break;
}
