<?php

namespace AppBundle\Entity;

use Algolia\AlgoliaSearchBundle\Mapping\Annotation as Algolia;
use AppBundle\Utils\EmojisRemover;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="event_invitations")
 * @ORM\Entity
 *
 * @Algolia\Index(autoIndex=false)
 */
class EventInvite
{
    use EntityIdentityTrait;

    /**
     * @var string
     *
     * @ORM\Column(length=50)
     */
    private $firstName = '';

    /**
     * @var string
     *
     * @ORM\Column(length=50)
     */
    private $lastName = '';

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $email = '';

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $message = '';

    /**
     * @var string|null
     *
     * @ORM\Column(length=50, nullable=true)
     */
    private $clientIp;

    /**
     * @var Event|null
     *
     * @ORM\ManyToOne(targetEntity="Event")
     */
    private $event;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(Event $event)
    {
        $this->uuid = Uuid::uuid4();
        $this->event = $event;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function __toString()
    {
        return 'Invitation à l\'évenement '.$this->event.' de '.$this->getSenderFullName().' à '.$this->email;
    }

    public static function create(string $firstName, string $lastName, string $email, string $clientIp, Event $event): self
    {
        $invite = new static($event);

        $invite->firstName = EmojisRemover::remove($firstName);
        $invite->lastName = EmojisRemover::remove($lastName);
        $invite->email = $email;
        $invite->clientIp = $clientIp;

        return $invite;
    }

    public function getSenderFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        if ($this->createdAt instanceof \DateTime) {
            $this->createdAt = \DateTimeImmutable::createFromMutable($this->createdAt);
        }

        return $this->createdAt;
    }
}
