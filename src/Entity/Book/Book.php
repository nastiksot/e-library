<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\AbstractEntity;
use App\Entity\Traits\DescriptionEntityTrait;
use App\Entity\Traits\NameEntityTrait;
use App\Entity\Traits\QuantityEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function array_filter;
use function implode;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="books",
 * )
 */
class Book extends AbstractEntity
{
    use NameEntityTrait;
    use DescriptionEntityTrait;
    use QuantityEntityTrait;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Book\Author", inversedBy="books")
     * @ORM\JoinTable(name="books_authors")
     *
     * @var Collection|ArrayCollection|Author[]
     */
    private Collection $authors;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Book\Category", inversedBy="books")
     * @ORM\JoinTable(name="books_categories")
     *
     * @var Collection|ArrayCollection|Author[]
     */
    private Collection $categories;

    public function __toString(): string
    {
        $labels = [];
        if (!$this->getId()) {
            $labels[] = 'New Book';
        } else {
            $labels[] = $this->getName();
        }

        return implode(' ', array_filter($labels));
    }

    public function __construct()
    {
        $this->authors    = new ArrayCollection();
        $this->categories = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

}
