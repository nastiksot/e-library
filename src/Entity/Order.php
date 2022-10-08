<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Dictionary\OrderStatus;
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
 *     },
 * )
 */
class Order extends AbstractEntity
{
    use QuantityEntityTrait;
    use StartAtEntityTrait;
    use EndAtEntityTrait;

    public const STATUS_OPEN     = 1;
    public const STATUS_DONE     = 2;
    public const STATUS_CANCELED = 3;

    public const STATUSES = [
        self::STATUS_OPEN     => 'Open',
        self::STATUS_DONE     => 'Done',
        self::STATUS_CANCELED => 'Canceled',
    ];

//    /**
//     * @ORM\Column(type="integer", options={"unsigned": true})
//     */
//    protected ?int $readingType = null;


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
     * @ORM\Column(name="status", type=OrderStatus::class, nullable=false, options={"default": "new"})
     */
    private OrderStatus $status;

    public function __construct()
    {
        $this->status = OrderStatus::OPEN();
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
}
