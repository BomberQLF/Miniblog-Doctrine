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
            include('./Vue/create_post.php');
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
                include('./Vue/miniblog.php');
            } else {
                include('./Vue/login.php');
                echo "Invalid Logins";
            }
        }
        break;

    case 'profile':
        $loggedUser ? include('./Vue/gestionProfile.php') : include('./Vue/login.php');
        break;

    case 'logout':
        session_unset();
        session_destroy();
        include('./Vue/miniblog.php');
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

                // Nettoyer le nom du fichier
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
                include('./Vue/archives.php');
            }
        } else {
            include('./Vue/login.php');
        }
        break;

    // case 'blogDetails':
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $id = $_GET['id'];
    //     }

    default:
        include('./Vue/miniblog.php');
        break;
}

// var_dump($_SESSION['user_id']);

// $rep = $entityManager->getRepository('Message');
// $rep->find(2); // Renvoie le message à l'ID 2
// $contenus = $rep->findAll(); // Renvoie un tableau (liste) de tous les messages
// $id = $rep->findBy(array('contenu' => 'ID'));

// $repUser = $entityManager->getRepository('Utilisateur');
// $uuid1 = $repUser->find(1);

// $msg1 = new Message();
// $msg1->setTitle("Mon premier message");
// $msg1->setContenu("Ceci est le contenu de mon message.");
// $msg1->setPostedAt(new \DateTime()); 
// $msg1->setUser($loggedUser);
// $entityManager->persist($msg1);

$entityManager->flush();

// echo "Message créé avec succès !";
// var_dump($msg1->getId());
// die();
// $newUser = Utilisateur::createUser($entityManager, 'test', '123');

// var_dump($contenus);
// foreach ($contenus as $contenu) {
//     echo $contenu->getContenu();
// }
?>