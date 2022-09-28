<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="authors_books",
 *
 *     indexes={
 *          @ORM\Index(name="fk_author_id", columns={"author_id"}),
 *          @ORM\Index(name="fk_book_id", columns={"book_id"}),
 *     }
 * )
 */
class AuthorBook extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Author", inversedBy="authorBooks")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected ?Author $author = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Book", inversedBy="authorBooks")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected ?Book $book = null;

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

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

}
