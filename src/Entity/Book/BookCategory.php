<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="books_categories",

 *     indexes={
 *          @ORM\Index(name="fk_book_id", columns={"book_id"}),
 *          @ORM\Index(name="fk_category_id", columns={"category_id"}),
 *     }
 * )
 */
class BookCategory extends AbstractEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Book", inversedBy="bookCategories")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    protected ?Book $book = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book\Category", inversedBy="bookCategories")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected ?Category $category = null;

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}
