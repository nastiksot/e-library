<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\AbstractEntity;
use App\Entity\Order;
use App\Entity\Reading;
use App\Entity\Stock;
use App\Entity\Traits\DescriptionEntityTrait;
use App\Entity\Traits\NameEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function array_filter;
use function implode;
use function method_exists;

/**
 * @ORM\Table(
 *     name="books",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Book\BookRepository")
 */
class Book extends AbstractEntity
{
    use NameEntityTrait;
    use DescriptionEntityTrait;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reading", mappedBy="book")
     */
    private Collection $readings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="book")
     */
    private Collection $orders;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Stock", mappedBy="book", cascade={"persist", "remove"})
     */
    private ?Stock $stock = null;

    public function __construct()
    {
        $this->authors    = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->readings   = new ArrayCollection();
        $this->orders     = new ArrayCollection();
    }

    public function __toString(): string
    {
        $labels = [];
        if (!$this->getId()) {
            $labels[] = 'New Book';
        } else {
            $labels[] = $this->getName();
        }

        if (method_exists($this, 'isActive') && !$this->isActive()) {
            $labels[] = '(disabled)';
        }

        return implode(' ', array_filter($labels));
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Reading>
     */
    public function getReadings(): Collection
    {
        return $this->readings;
    }

    public function addReading(Reading $reading): self
    {
        if (!$this->readings->contains($reading)) {
            $this->readings->add($reading);
            $reading->setBook($this);
        }

        return $this;
    }

    public function removeReading(Reading $reading): self
    {
        if ($this->readings->removeElement($reading)) {
            // set the owning side to null (unless already changed)
            if ($reading->getBook() === $this) {
                $reading->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setBook($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getBook() === $this) {
                $order->setBook(null);
            }
        }

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        // unset the owning side of the relation if necessary
        if ($stock === null && $this->stock !== null) {
            $this->stock->setBook(null);
        }

        // set the owning side of the relation if necessary
        if ($stock !== null && $stock->getBook() !== $this) {
            $stock->setBook($this);
        }

        $this->stock = $stock;

        return $this;
    }
}
