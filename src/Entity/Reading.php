<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="reading"
 * )
 */
class Reading extends AbstractEntity
{

    public const READING_TYPE_SUBSCRIPTION = 1;
    public const READING_TYPE_READING_ROOM = 2;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    protected ?int $readingType = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book", inversedBy="reading")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    protected ?Book $book;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reading")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected ?User $user;

    /**
     * @ORM\Column(name="start_at", type="date", nullable=true)
     */
    protected ?DateTimeInterface $startAt = null;

    /**
     * @ORM\Column(name="end_at", type="date", nullable=true)
     */
    protected ?DateTimeInterface $endAt = null;

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

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

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

}
