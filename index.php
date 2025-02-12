<?php 
// session_start();
require_once('bootstrap.php');

// $action = isset($_GET['action']) ? $_GET['action'] : '';
// switch ($action) {
//     case 'value':
//         # code...
//         break;
    
//     default:
//         include('./Vue/miniblog.php');
//         break;
// }



// AFFICHAGE


$rep = $entityManager->getRepository('Message');
$rep->find(2); // Renvoie le message à l'ID 2
$contenus = $rep->findAll(); // Renvoie un tableau (liste) de tous les messages
$id = $rep->findBy(array('contenu'=> 'ID'));

$repUser = $entityManager->getRepository('Utilisateur');
$uuid1 = $repUser->find(1);
// var_dump($uuid1);

$msg1 = new Message();
$msg1->setContenu('ceci est mon premier message');
$msg1->setUser($uuid1);

$entityManager->persist($msg1);
$entityManager->flush();

// $newUser = Utilisateur::createUser($entityManager, 'TestManuel', '123');

// var_dump($contenus);
foreach ($contenus as $contenu) {
    echo $contenu->getContenu();
}
?>