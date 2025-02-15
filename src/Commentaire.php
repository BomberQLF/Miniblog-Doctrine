<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

#[ORM\Entity]
#[ORM\Table(name: 'commentaire')]
class Commentaire
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(name: 'commentaire', length: 255)]
    private string $commentaire;

    #[ORM\Column(name: 'posted_at', type: 'datetime', nullable: true)]
    private ?\DateTime $postedAt;

    // Clé étrangère vers Utilisateur
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: "CASCADE")]
    private Utilisateur $user;

    // Clé étrangère vers Message
    #[ORM\ManyToOne(targetEntity: Message::class)]
    #[ORM\JoinColumn(name: 'message_id', referencedColumnName: 'id', nullable: false, onDelete: "CASCADE")]
    private Message $message;

    // Getters et Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function getPostedAt(): ?\DateTime
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTime $postedAt): void
    {
        $this->postedAt = $postedAt;
    }

    public function getUser(): Utilisateur
    {
        return $this->user;
    }

    public function setUser(Utilisateur $user): void
    {
        $this->user = $user;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }

    public static function getCommentsByMessageId(EntityManagerInterface $entityManager, int $messageId): array
    {
        return $entityManager->getRepository(self::class)->findBy(
            ['message' => $messageId], 
            ['postedAt' => 'DESC']
        );
    }

    public static function postComment(EntityManagerInterface $entityManager, $messageId, $userId, $contenu): void
    {
        $message = $entityManager->getRepository(Message::class)->find($messageId);
        $user = $entityManager->getRepository(Utilisateur::class)->find($userId);

        if ($message && $user) {
            $comment = new Commentaire();
            $comment->setCommentaire($contenu);
            $comment->setPostedAt(new \DateTime());
            $comment->setUser($user);
            $comment->setMessage($message);

            $entityManager->persist($comment);
            $entityManager->flush();
        }
    }

    public static function deleteCommentById(EntityManagerInterface $entityManager, $idComment):void 
    {
        $rep = $entityManager->getRepository(self::class)->find($idComment);
        $entityManager->remove($rep);
        $entityManager->flush();
    }

    public static function showAllComments(EntityManagerInterface $entityManager): array
    {
        $rep = $entityManager->getRepository(self::class)->findAll();
        return $rep;
    }
}