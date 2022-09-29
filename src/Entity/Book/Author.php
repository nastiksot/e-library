<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\AbstractEntity;
use App\Entity\Traits\Contact\FullNameEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function array_filter;
use function implode;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="authors",
 * )
 */
class Author extends AbstractEntity
{

    use FullNameEntityTrait;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Book\Book", mappedBy="authors")
     *
     * @var Collection|ArrayCollection|Book
     */
    private Collection $books;

    public function __toString(): string
    {
        $labels = [];
        if (!$this->getId()) {
            $labels[] = 'New Author';
        } else {
            $labels[] = $this->getFirstName();
            $labels[] = $this->getLastName();
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
            $book->addAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeAuthor($this);
        }

        return $this;
    }
}
