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
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
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
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order extends AbstractEntity
{
    use QuantityEntityTrait;
    use StartAtEntityTrait;
    use EndAtEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Book", inversedBy="orders")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Book $book;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Reading", mappedBy="order")
     */
    private Collection $readings;

    /**
     * @ORM\Column(name="reading_type", type=ReadingType::class, nullable=false, options={"default": "reading-hall"})
     */
    private ReadingType $readingType;

    public function __construct()
    {
        $this->status      = OrderStatus::OPEN();
        $this->readingType = ReadingType::READING_HALL();
        $this->readings    = new ArrayCollection();
    }

    public function getStatus(): OrderStatus
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

    /**
     * @return Collection<int, Reading>
     */
    public function getReadings(): Collection
    {
        return $this->readings;
    }

    public function addReading(Reading $reading): self
    {
        if (!$this->readings->contains($reading)) {
            $this->readings->add($reading);
            $reading->setOrder($this);
        }

        return $this;
    }

    public function removeReading(Reading $reading): self
    {
        if ($this->readings->removeElement($reading)) {
            // set the owning side to null (unless already changed)
            if ($reading->getOrder() === $this) {
                $reading->setOrder(null);
            }
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
