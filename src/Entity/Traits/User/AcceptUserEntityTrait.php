<?php

declare(strict_types=1);

namespace App\Entity\Traits\User;

use Doctrine\ORM\Mapping as ORM;

trait AcceptUserEntityTrait
{
    /**
     * @ORM\Column(name="accept_news", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $acceptNews = false;

    /**
     * @ORM\Column(name="accept_process_personal_data", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $acceptProcessPersonalData = false;

    /**
     * @ORM\Column(name="accept_privacy_policy", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $acceptPrivacyPolicy = false;

    public function isAcceptNews(): bool
    {
        return $this->acceptNews;
    }

    public function setAcceptNews(bool $acceptNews): self
    {
        $this->acceptNews = $acceptNews;

        return $this;
    }

    public function isAcceptProcessPersonalData(): bool
    {
        return $this->acceptProcessPersonalData;
    }

    public function setAcceptProcessPersonalData(bool $acceptProcessPersonalData): self
    {
        $this->acceptProcessPersonalData = $acceptProcessPersonalData;

        return $this;
    }

    public function isAcceptPrivacyPolicy(): bool
    {
        return $this->acceptPrivacyPolicy;
    }

    public function setAcceptPrivacyPolicy(bool $acceptPrivacyPolicy): self
    {
        $this->acceptPrivacyPolicy = $acceptPrivacyPolicy;

        return $this;
    }
}
