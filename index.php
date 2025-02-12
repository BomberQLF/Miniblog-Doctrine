<?php
session_start();
require_once('bootstrap.php');

$loggedUser = Utilisateur::isLoggedIn($entityManager);
$isAdmin = Utilisateur::isAdmin($entityManager);

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

    default:
        include('./Vue/miniblog.php');
        break;
}

var_dump($_SESSION['user_id']);

$rep = $entityManager->getRepository('Message');
$rep->find(2); // Renvoie le message à l'ID 2
$contenus = $rep->findAll(); // Renvoie un tableau (liste) de tous les messages
$id = $rep->findBy(array('contenu' => 'ID'));

$repUser = $entityManager->getRepository('Utilisateur');
$uuid1 = $repUser->find(1);
// var_dump($uuid1);

// $msg1 = new Message();
// // $msg1->setContenu('ceci est mon premier message');
// $msg1->setUser($uuid1);

// $entityManager->persist($msg1);
// $entityManager->flush();

// $newUser = Utilisateur::createUser($entityManager, 'test', '123');

// var_dump($contenus);
// foreach ($contenus as $contenu) {
//     echo $contenu->getContenu();
// }
?>