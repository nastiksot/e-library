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
use function method_exists;

/**
 * @ORM\Table(
 *     name="authors",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Book\AuthorRepository")
 */
class Author extends AbstractEntity
{
    use NameEntityTrait;

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
            $labels[] = $this->getName();
        }

        if (method_exists($this, 'isActive') && !$this->isActive()) {
            $labels[] = '(disabled)';
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
