<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\UserInterface;
use App\Entity\Traits\ActiveEntityTrait;
use App\Entity\Traits\Contact\FirstNameEntityTrait;
use App\Entity\Traits\Contact\LastNameEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     indexes={
 *          @ORM\Index(name="idx_active", columns={"active"}),
 *     }
 * )
 * @ORM\EntityListeners({"App\EventListener\Doctrine\UserEntityListener"})
 */
class User extends AbstractEntity implements UserInterface
{

    use FirstNameEntityTrait,
        LastNameEntityTrait,
        ActiveEntityTrait;

    /**
     * @ORM\ManyToMany(targetEntity="Book", inversedBy="authors")
     * @ORM\JoinTable(name="books_authors")
     */
    protected Collection $booksAuthors;

    /**
     * @ORM\OneToMany(targetEntity="Book", mappedBy="reader")
     */
    protected Collection $booksReaders;

    /**
     * @ORM\Column(name="username", type="string", length=60, nullable=false)
     */
    protected ?string $username = null;

    /**
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    protected ?string $password = null;

    /**
     * @var null|string
     * not mapped property
     */
    protected ?string $plainPassword = null;

    /**
     * @ORM\Column(name="email", type="string", length=60, nullable=true)
     */
    protected ?string $email = null;

    /**
     * @ORM\Column(name="salt", type="string", nullable=false)
     */
    protected string $salt;

    /**
     * @ORM\Column(name="roles", type="json", nullable=false)
     */
    protected array $roles = [UserInterface::ROLE_USER];

    public function __toString(): string
    {
        return $this->getUsername() ?? 'New User';
    }

    public function __construct()
    {
        try {
            $this->salt = bin2hex(random_bytes(12));
        } catch (\Exception $e) {
            $this->salt = '';
        }
        $this->booksAuthors = new ArrayCollection();
        $this->booksReaders = new ArrayCollection();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->hasRole(self::ROLE_ADMIN);
    }

    public function isUser(): bool
    {
        return (bool)$this->getId();
    }

    public function isAuthor(): bool
    {
        return $this->isAdmin() || $this->hasRole(self::ROLE_AUTHOR);
    }

    public function isReader(): bool
    {
        return $this->isUser() || $this->hasRole(self::ROLE_READER);
    }

    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        if ($plainPassword) {
            //$this->setUpdatedAt(new DateTime());
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function getRole(): ?string
    {
        return $this->roles[0] ?? '';
    }

    public function eraseCredentials()
    {
    }

    public function serialize(): ?string
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
                $this->salt,
            ]
        );
    }

    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ] = unserialize($serialized, ['allowed_classes' => [self::class]]);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooksAuthors(): Collection
    {
        return $this->booksAuthors;
    }

    public function addBooksAuthor(Book $booksAuthor): self
    {
        if (!$this->booksAuthors->contains($booksAuthor)) {
            $this->booksAuthors[] = $booksAuthor;
        }

        return $this;
    }

    public function removeBooksAuthor(Book $booksAuthor): self
    {
        $this->booksAuthors->removeElement($booksAuthor);

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooksReaders(): Collection
    {
        return $this->booksReaders;
    }

    public function addBooksReader(Book $booksReader): self
    {
        if (!$this->booksReaders->contains($booksReader)) {
            $this->booksReaders[] = $booksReader;
            $booksReader->setReader($this);
        }

        return $this;
    }

    public function removeBooksReader(Book $booksReader): self
    {
        if ($this->booksReaders->removeElement($booksReader)) {
            // set the owning side to null (unless already changed)
            if ($booksReader->getReader() === $this) {
                $booksReader->setReader(null);
            }
        }

        return $this;
    }

}
