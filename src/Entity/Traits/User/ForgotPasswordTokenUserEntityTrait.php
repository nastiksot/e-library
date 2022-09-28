<?php

declare(strict_types=1);

namespace App\Entity\Traits\User;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

trait ForgotPasswordTokenUserEntityTrait
{
    /**
     * @ORM\Column(name="forgot_password_token", type="uuid", nullable=true)
     */
    protected ?UuidV4 $forgotPasswordToken = null;

    /**
     * @ORM\Column(name="forgot_password_valid_at", type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $forgotPasswordValidAt = null;

    public function getForgotPasswordToken(): ?UuidV4
    {
        return $this->forgotPasswordToken;
    }

    public function setForgotPasswordToken(?UuidV4 $forgotPasswordToken): self
    {
        $this->forgotPasswordToken = $forgotPasswordToken;

        return $this;
    }

    public function getForgotPasswordValidAt(): ?DateTimeInterface
    {
        return $this->forgotPasswordValidAt;
    }

    public function setForgotPasswordValidAt(?DateTimeInterface $forgotPasswordValidAt): self
    {
        $this->forgotPasswordValidAt = $forgotPasswordValidAt;

        return $this;
    }
}
