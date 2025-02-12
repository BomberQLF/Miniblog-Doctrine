<?php

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'messages')]

class Message
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;
    #[ORM\Column(length: 255)]
    private string $contenu;
    #[ORM\Column(name: 'posted_at', nullable: true)]
    private $postedAt;
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $user;



    function getId(): int
    {
        return $this->id;
    }
    function setId($id): void
    {
        $this->id = $id;
    }
    function setContenu($contenu): void
    {
        $this->contenu = $contenu;
    }
    function getContenu(): string
    {
        return $this->contenu;
    }
    function setPostedAt($time): void
    {
        $this->postedAt = $time;
    }
    function getPostedAt(): string
    {
        return $this->postedAt;
    }
    public function setUser(Utilisateur $user): void
    {
        $this->user = $user;
    }

    public function getUser(): Utilisateur
    {
        return $this->user;
    }
}