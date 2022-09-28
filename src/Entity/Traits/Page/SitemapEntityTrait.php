<?php

declare(strict_types=1);

namespace App\Entity\Traits\Page;

use Doctrine\ORM\Mapping as ORM;

trait SitemapEntityTrait
{
    /**
     * @ORM\Column(name="indexed", type="boolean", nullable=false, options={"default": 1})
     */
    protected bool $indexed = true;

    /**
     * @ORM\Column(name="sitemap_change_frequency", type="string", length=255, options={"default": "weekly"})
     */
    protected string $sitemapChangeFrequency = 'weekly';

    /**
     * @ORM\Column(name="sitemap_priority", type="float", options={"default": 0.5})
     */
    protected float $sitemapPriority = 0.5;

    public function getIndexed(): bool
    {
        return $this->indexed;
    }

    public function isIndexed(): bool
    {
        return $this->indexed;
    }

    public function setIndexed(bool $indexed): self
    {
        $this->indexed = $indexed;

        return $this;
    }

    public function getSitemapChangeFrequency(): ?string
    {
        return $this->sitemapChangeFrequency;
    }

    public function setSitemapChangeFrequency(string $sitemapChangeFrequency): self
    {
        $this->sitemapChangeFrequency = $sitemapChangeFrequency;

        return $this;
    }

    public function getSitemapPriority(): ?float
    {
        return $this->sitemapPriority;
    }

    public function setSitemapPriority(float $sitemapPriority): self
    {
        $this->sitemapPriority = $sitemapPriority;

        return $this;
    }
}
