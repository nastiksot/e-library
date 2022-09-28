<?php

declare(strict_types=1);

namespace App\Entity\Traits\User;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

trait RegisterConfirmTokenUserEntityTrait
{
    /**
     * @ORM\Column(name="register_confirm_token", type="uuid", nullable=true)
     */
    protected ?UuidV4 $registerConfirmToken = null;

    /**
     * @ORM\Column(name="register_confirm_valid_at", type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $registerConfirmValidAt = null;

    public function getRegisterConfirmToken(): ?UuidV4
    {
        return $this->registerConfirmToken;
    }

    public function setRegisterConfirmToken(?UuidV4 $registerConfirmToken): self
    {
        $this->registerConfirmToken = $registerConfirmToken;

        return $this;
    }

    public function getRegisterConfirmValidAt(): ?DateTimeInterface
    {
        return $this->registerConfirmValidAt;
    }

    public function setRegisterConfirmValidAt(?DateTimeInterface $registerConfirmValidAt): self
    {
        $this->registerConfirmValidAt = $registerConfirmValidAt;

        return $this;
    }
}
