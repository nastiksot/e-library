<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\DescriptionEntityTrait;
use App\Entity\Traits\QuantityEntityTrait;
use App\Entity\Traits\TitleEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="books"
 * )
 */
class Book extends AbstractEntity
{
    use TitleEntityTrait,
        DescriptionEntityTrait,
        QuantityEntityTrait;

    /**
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="books")
     */
    protected Collection $authors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reading", mappedBy="book")
     */
    protected Collection $reading;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->reading = new ArrayCollection();
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection|Reading[]
     */
    public function getReading(): Collection
    {
        return $this->reading;
    }

    public function addReading(Reading $reading): self
    {
        if (!$this->reading->contains($reading)) {
            $this->reading[] = $reading;
            $reading->setBook($this);
        }

        return $this;
    }

    public function removeReading(Reading $reading): self
    {
        if ($this->reading->removeElement($reading)) {
            // set the owning side to null (unless already changed)
            if ($reading->getBook() === $this) {
                $reading->setBook(null);
            }
        }

        return $this;
    }

}
