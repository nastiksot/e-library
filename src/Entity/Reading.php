<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Dictionary\ReadingType;
use App\Entity\Book\Book;
use App\Entity\Traits\PenaltyEntityTrait;
use App\Entity\Traits\QuantityEntityTrait;
use App\Entity\Traits\Timestampable\EndAtEntityTrait;
use App\Entity\Traits\Timestampable\ProlongAtEntityTrait;
use App\Entity\Traits\Timestampable\StartAtEntityTrait;
use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="reading",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *          @ORM\Index(name="idx_start_at", columns={"start_at"}),
 *          @ORM\Index(name="idx_end_at", columns={"end_at"}),
 *          @ORM\Index(name="idx_prolong_at", columns={"prolong_at"}),
 *          @ORM\Index(name="fk_book_id", columns={"book_id"}),
 *          @ORM\Index(name="fk_user_id", columns={"user_id"}),
 *          @ORM\Index(name="fk_order_id", columns={"order_id"}),
 *          @ORM\Index(name="idx_reading_type", columns={"reading_type"}),
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ReadingRepository")
 */
class Reading extends AbstractEntity
{
    use QuantityEntityTrait;
    use StartAtEntityTrait;
    use EndAtEntityTrait;
    use ProlongAtEntityTrait;
    use PenaltyEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Book", inversedBy="readings")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?Book $book = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="readings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?User $user = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="readings")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private ?Order $order = null;

    /**
     * @ORM\Column(name="reading_type", type=ReadingType::class, nullable=false, options={"default": "reading-hall"})
     */
    private ReadingType $readingType;


    public function __construct()
    {
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

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

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
