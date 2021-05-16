<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\DescriptionEntityTrait;
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
        DescriptionEntityTrait;

    /**
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="books")
     */
    protected Collection $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
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

}
