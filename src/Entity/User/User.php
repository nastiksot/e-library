<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Contracts\Entity\UserInterface;
use App\Entity\AbstractEntity;
use App\Entity\Order;
use App\Entity\Reading;
use App\Entity\Traits\ActiveEntityTrait;
use App\Entity\Traits\Contact\FullNameEntityTrait;
use App\Entity\Traits\User\GeneralDataUserEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

use function array_filter;
use function implode;
use function method_exists;

/**
 * @ORM\Table(
 *     name="users",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *          @ORM\Index(name="idx_active", columns={"active"}),
 *     },
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_email", columns={"email"}),
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\EntityListeners({"App\EventListener\Doctrine\UserEntityListener"})
 */
class User extends AbstractEntity implements UserInterface
{
    use ActiveEntityTrait;
    use GeneralDataUserEntityTrait;
    use FullNameEntityTrait;

    public function __toString(): string
    {
        $labels = [];
        if (!$this->getId()) {
            $labels[] = 'New User';
        } else {
            $labels[] = $this->getFullName();
        }

        if (method_exists($this, 'isActive') && !$this->isActive()) {
            $labels[] = '(disabled)';
        }

        return implode(' ', array_filter($labels));
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reading", mappedBy="user")
     */
    private Collection $readings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user")
     */
    private Collection $orders;

    public function penalty(): ?float
    {
        $penalty = 0;

        foreach ($this->getReadings() as $reading) {
            $penalty += $reading->getPenalty();
        }

        return $penalty;
    }

    #[Pure]
    public function __construct()
    {
        $this->active   = false;
        $this->roles    = [UserInterface::ROLE_USER];
        $this->readings = new ArrayCollection();
        $this->orders   = new ArrayCollection();
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
        if (!$this->reading->contains($reading)) {
            $this->reading[] = $reading;
            $reading->setUser($this);
        }

        return $this;
    }

    public function removeReading(Reading $reading): self
    {
        if ($this->reading->removeElement($reading)) {
            // set the owning side to null (unless already changed)
            if ($reading->getUser() === $this) {
                $reading->setUser(null);
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
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

}
