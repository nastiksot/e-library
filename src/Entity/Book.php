<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ActiveEntityTrait;
use App\Entity\Traits\TitleEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ORM\Table(
 *     name="books",
 *     indexes={
 *     }
 * )
 */
class Book extends AbstractEntity
{
    use TitleEntityTrait;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="booksAuthors")
     */
    protected Collection $authors;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="booksReaders")
     * @ORM\JoinColumn(name="reader_id", referencedColumnName="id")
     */
    protected ?User $reader = null;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(User $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBooksAuthor($this);
        }

        return $this;
    }

    public function removeAuthor(User $author): self
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBooksAuthor($this);
        }

        return $this;
    }

    public function getReader(): ?User
    {
        return $this->reader;
    }

    public function setReader(?User $reader): self
    {
        $this->reader = $reader;

        return $this;
    }

}
