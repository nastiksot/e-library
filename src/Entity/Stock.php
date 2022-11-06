<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Book\Book;
use App\Entity\Traits\StockEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="stoks",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *          @ORM\Index(name="fk_book_id", columns={"book_id"}),
 *          @ORM\Index(name="idx_quantity", columns={"quantity"}),
 *          @ORM\Index(name="idx_reserved", columns={"reserved"}),
 *          @ORM\Index(name="idx_stock", columns={"book_id", "quantity", "reserved"}),
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository")
 */
class Stock extends AbstractEntity
{
    use StockEntityTrait;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Book\Book", inversedBy="stock")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?Book $book = null;

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
