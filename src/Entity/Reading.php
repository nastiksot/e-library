<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Book\Book;
use App\Entity\Traits\QuantityEntityTrait;
use App\Entity\Traits\Timestampable\EndAtEntityTrait;
use App\Entity\Traits\Timestampable\ProlongAtEntityTrait;
use App\Entity\Traits\Timestampable\StartAtEntityTrait;
use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
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
 *     },
 * )
 */
class Reading extends AbstractEntity
{

    use QuantityEntityTrait;
    use StartAtEntityTrait;
    use EndAtEntityTrait;
    use ProlongAtEntityTrait;

    public const READING_TYPE_SUBSCRIPTION = 1;
    public const READING_TYPE_READING_ROOM = 2;

    public const READING_TYPES = [
        self::READING_TYPE_SUBSCRIPTION => 'Subscription',
        self::READING_TYPE_READING_ROOM => 'Reading Hall',
    ];

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    protected ?int $readingType = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Book", inversedBy="reading")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected ?Book $book;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="reading")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected ?User $user;

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

    public function getReadingType(): ?int
    {
        return $this->readingType;
    }

    public function setReadingType(int $readingType): self
    {
        $this->readingType = $readingType;

        return $this;
    }

}
