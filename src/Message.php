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
}