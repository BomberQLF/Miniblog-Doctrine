<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'utilisateurs')]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(name: 'login', length: 255, unique: true)]
    private string $login;

    #[ORM\Column(name: 'password', length: 255)]
    private string $password;

    public function getIdUser(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPwd(): string
    {
        return $this->password;
    }

    public function setPwd(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPwd(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public static function createUser(EntityManagerInterface $entityManager, string $login, string $password): Utilisateur
    {
        $existingUser = self::findByLogin($entityManager, $login);
        if ($existingUser) {
            throw new Exception("User already exists.");
        }

        $newUser = new self();
        $newUser->setLogin($login);
        $newUser->setPwd($password);

        $entityManager->persist($newUser);
        $entityManager->flush();

        return $newUser;
    }

    public static function findByLogin(EntityManagerInterface $entityManager, string $login): ?self
    {
        return $entityManager->getRepository(self::class)->findOneBy(['login' => $login]);
    }

    public static function isLoggedIn(EntityManagerInterface $entityManager): ?Utilisateur
    {   
        if (!isset($_SESSION['user_id'])) {
            return null; 
        }
    
        return $entityManager->getRepository(self::class)->find($_SESSION['user_id']);
    }

    public static function isAdmin(EntityManagerInterface $entityManager): bool
    {
        $user = self::isLoggedIn($entityManager);
        return $user && $user->getIdUser() === 4;
    }
}