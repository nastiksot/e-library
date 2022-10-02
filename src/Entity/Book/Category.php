<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\AbstractEntity;
use App\Entity\Traits\NameEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function array_filter;
use function implode;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="categories",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *     },
 * )
 */
class Category extends AbstractEntity
{
    use NameEntityTrait;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Book\Book", mappedBy="categories")
     *
     * @var Collection|ArrayCollection|Book
     */
    private Collection $books;


    public function __toString(): string
    {
        $labels = [];
        if (!$this->getId()) {
            $labels[] = 'New Category';
        } else {
            $labels[] = $this->getName();
        }

        return implode(' ', array_filter($labels));
    }

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeCategory($this);
        }

        return $this;
    }

}
