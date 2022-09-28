<?php

declare(strict_types=1);

namespace App\Entity\Traits\Page;

use Doctrine\ORM\Mapping as ORM;

trait SeoEntityTrait
{
    /**
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     */
    protected ?string $metaTitle = null;

    /**
     * @ORM\Column(name="meta_keywords", type="string", length=1000, nullable=true)
     */
    protected ?string $metaKeywords = null;

    /**
     * @ORM\Column(name="meta_description", type="string", length=1000, nullable=true)
     */
    protected ?string $metaDescription = null;

    /**
     * @ORM\Column(name="logo_title", type="string", length=255, nullable=true)
     */
    protected ?string $logoTitle = null;

    /**
     * @ORM\Column(name="og_title", type="string", length=255, nullable=true)
     */
    protected ?string $ogTitle = null;

    /**
     * @ORM\Column(name="og_description", type="text", nullable=true)
     */
    protected ?string $ogDescription = null;

    /**
     * @ORM\Column(name="og_image", type="string", length=1000, nullable=true)
     */
    protected ?string $ogImage = null;

    /**
     * @ORM\Column(name="additional_meta_tags", type="text", nullable=true)
     */
    protected ?string $additionalMetaTags = null;

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getLogoTitle(): ?string
    {
        return $this->logoTitle;
    }

    public function setLogoTitle(?string $logoTitle): self
    {
        $this->logoTitle = $logoTitle;

        return $this;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function setOgTitle(?string $ogTitle): self
    {
        $this->ogTitle = $ogTitle;

        return $this;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function setOgDescription(?string $ogDescription): self
    {
        $this->ogDescription = $ogDescription;

        return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function setOgImage(?string $ogImage): self
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    public function getAdditionalMetaTags(): ?string
    {
        return $this->additionalMetaTags;
    }

    public function setAdditionalMetaTags(?string $additionalMetaTags): self
    {
        $this->additionalMetaTags = $additionalMetaTags;

        return $this;
    }
}
