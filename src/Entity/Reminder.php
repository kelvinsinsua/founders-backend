<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReminderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\ApiProperty;

#[ORM\Entity(repositoryClass: ReminderRepository::class)]
#[ApiResource(
    operations: [new Post()],
    normalizationContext: ['groups' => ['reminderRead']],
    denormalizationContext: ['groups' => ['reminderWrite']],
)]
#[ApiResource(
    uriTemplate: '/users/{id}/reminders', 
    uriVariables: [
        'id' => new Link(
            fromClass: User::class,
            fromProperty: 'reminders'
        )
    ], 
    operations: [new GetCollection()],
    normalizationContext: ['groups' => ['reminderRead']],
    denormalizationContext: ['groups' => ['reminderWrite']]
)]
class Reminder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reminderRead'])]
    private ?int $id = null;

    #[ORM\Column()]
    #[Groups(['reminderRead', 'reminderWrite'])]
    private ?bool $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['reminderRead', 'reminderWrite'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date'
        ]
    )]
    private ?\DateTimeInterface $reminderDate = null;

    #[ORM\Column]
    #[Groups(['reminderRead'])]
    private ?bool $sent = false;

    #[ORM\Column(nullable: true)]
    #[Groups(['reminderRead', 'reminderWrite'])]
    private ?bool $sms = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['reminderRead', 'reminderWrite'])]
    private ?string $reminderEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['reminderRead', 'reminderWrite'])]
    private ?string $reminderPhone = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['reminderRead', 'reminderWrite'])]
    private ?string $reminderMessage = null;

    #[ORM\ManyToOne(inversedBy: 'reminders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reminderWrite'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '/api/users/{id}'
        ]
    )]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?bool
    {
        return $this->email;
    }

    public function setEmail(bool $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getReminderDate(): ?\DateTimeInterface
    {
        return $this->reminderDate;
    }

    public function setReminderDate(\DateTimeInterface $reminderDate): self
    {
        $this->reminderDate = $reminderDate;

        return $this;
    }

    public function isSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    public function isSms(): ?bool
    {
        return $this->sms;
    }

    public function setSms(?bool $sms): self
    {
        $this->sms = $sms;

        return $this;
    }

    public function getReminderEmail(): ?string
    {
        return $this->reminderEmail;
    }

    public function setReminderEmail(?string $reminderEmail): self
    {
        $this->reminderEmail = $reminderEmail;

        return $this;
    }

    public function getReminderPhone(): ?string
    {
        return $this->reminderPhone;
    }

    public function setReminderPhone(?string $reminderPhone): self
    {
        $this->reminderPhone = $reminderPhone;

        return $this;
    }

    public function getReminderMessage(): ?string
    {
        return $this->reminderMessage;
    }

    public function setReminderMessage(string $reminderMessage): self
    {
        $this->reminderMessage = $reminderMessage;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
