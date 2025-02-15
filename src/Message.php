<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

#[ORM\Entity]
#[ORM\Table(name: 'messages')]
class Message
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(length: 255, nullable: false)]
    private string $contenu;

    #[ORM\Column(name: 'posted_at', type: 'datetime', nullable: true)]
    private ?\DateTime $postedAt;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getPostedAt(): ?\DateTime
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTime $time): void
    {
        $this->postedAt = $time;
    }

    public function setUser(Utilisateur $user): void
    {
        $this->user = $user;
    }

    public function getUser(): Utilisateur
    {
        return $this->user;
    }

    public static function getLastMessages(EntityManagerInterface $entityManager): array
    {
        return $entityManager->getRepository(self::class)->findBy([], ['id' => 'DESC'], 3);
    }
    public static function getAllMessages(EntityManagerInterface $entityManeger): array
    {
        return $entityManeger->getRepository(self::class)->findAll();
    }
    public static function deleteMessage(EntityManagerInterface $entityManager, $id): void
    {
        $message = $entityManager->getRepository(self::class)->find($id);
        $entityManager->remove($message);
        $entityManager->flush();
    }
    public static function createMessage(EntityManagerInterface $entityManager, string $title, string $contenu, int $user_id): void
    {
        $user = $entityManager->getRepository(Utilisateur::class)->find($user_id);

        $message = new Message();
        $message->setTitle($title);
        $message->setContenu($contenu);
        $message->setPostedAt(new \DateTime());
        $message->setUser($user);

        $entityManager->persist($message);
        $entityManager->flush();
    }
    public static function showMessageById(EntityManagerInterface $entityManager, $id): ?Message
    {
        return $entityManager->getRepository(self::class)->find($id);
    }

    public static function updatePost(EntityManagerInterface $entityManager, int $postId, string $title ,string $contenu): ?self
    {
        $post = $entityManager->getRepository(self::class)->find($postId);
        $post->setContenu($contenu);
        $post->setTitle($title);

        $entityManager->persist($post);
        $entityManager->flush();

        return $post;
    }


}