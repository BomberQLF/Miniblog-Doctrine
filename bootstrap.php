<?php
// bootstrap.php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Attributes
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/src'],
    isDevMode: true,
);

$connectionParams = [
    'dbname' => 'blog_doctrine',
    'user' => 'root',
    'password' => 'root',
    'host' => '127.0.0.1:8889',
    'driver' => 'pdo_mysql',
];
$connection = DriverManager::getConnection($connectionParams);


// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);