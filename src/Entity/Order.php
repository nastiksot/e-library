<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Dictionary\OrderStatus;
use App\Contracts\Dictionary\ReadingType;
use App\Entity\Book\Book;
use App\Entity\Traits\QuantityEntityTrait;
use App\Entity\Traits\Timestampable\EndAtEntityTrait;
use App\Entity\Traits\Timestampable\StartAtEntityTrait;
use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="orders",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *          @ORM\Index(name="idx_start_at", columns={"start_at"}),
 *          @ORM\Index(name="idx_end_at", columns={"end_at"}),
 *          @ORM\Index(name="fk_book_id", columns={"book_id"}),
 *          @ORM\Index(name="fk_user_id", columns={"user_id"}),
 *          @ORM\Index(name="idx_status", columns={"status"}),
 *          @ORM\Index(name="idx_reading_type", columns={"reading_type"}),
 *     },
 * )
 */
class Order extends AbstractEntity
{
    use QuantityEntityTrait;
    use StartAtEntityTrait;
    use EndAtEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Book", inversedBy="orders")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Book $book;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?User $user;

    /**
     * @ORM\Column(name="status", type=OrderStatus::class, nullable=false, options={"default": "open"})
     */
    private OrderStatus $status;

    /**
     * @ORM\Column(name="reading_type", type=ReadingType::class, nullable=false, options={"default": "reading-hall"})
     */
    private ReadingType $readingType;

    public function __construct()
    {
        $this->status      = OrderStatus::OPEN();
        $this->readingType = ReadingType::READING_HALL();
    }

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

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(null|string|OrderStatus $status): self
    {
        if (is_string($status)) {
            $this->status = new OrderStatus($status);
        } else {
            $this->status = $status;
        }

        return $this;
    }

    public function getReadingType(): ?ReadingType
    {
        return $this->readingType;
    }

    public function setReadingType(null|string|ReadingType $readingType): self
    {
        if (is_string($readingType)) {
            $this->readingType = new ReadingType($readingType);
        } else {
            $this->readingType = $readingType;
        }

        return $this;
    }
}
