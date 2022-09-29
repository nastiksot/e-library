<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Contracts\Entity\UserInterface;
use App\Entity\AbstractEntity;
use App\Entity\Reading;
use App\Entity\Traits\ActiveEntityTrait;
use App\Entity\Traits\Contact\FullNameEntityTrait;
use App\Entity\Traits\User\GeneralDataUserEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Table(
 *     name="users",
 *     indexes={
 *          @ORM\Index(name="idx_active", columns={"active"}),
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *     },
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_email", columns={"email"}),
 *     }
 * )
 * @ORM\EntityListeners({"App\EventListener\Doctrine\UserEntityListener"})
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 */
class User extends AbstractEntity implements UserInterface
{
    use ActiveEntityTrait;
    use GeneralDataUserEntityTrait;
    use FullNameEntityTrait;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reading", mappedBy="user")
     */
    protected Collection $reading;

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user")
//     */
//    protected Collection $orders;

    #[Pure]
    public function __construct()
    {
        $this->active = false;
        $this->roles  = [UserInterface::ROLE_USER];
        $this->reading = new ArrayCollection();
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

}
