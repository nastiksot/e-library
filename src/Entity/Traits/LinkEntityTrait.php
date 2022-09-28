<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Contracts\Entity\LinkEntityInterface;
use App\Entity\Page\AbstractPage;
use Doctrine\ORM\Mapping as ORM;

trait LinkEntityTrait
{
    /**
     * @ORM\Column(name="link_type", type="string", length=10, nullable=true)
     */
    protected ?string $linkType = LinkEntityInterface::LINK_TYPE_EXTERNAL_LINK;

    /**
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    protected ?string $link = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page\AbstractPage")
     * @ORM\JoinColumn(name="link_page_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected ?AbstractPage $linkPage = null;

    /**
     * @ORM\Column(name="link_target", type="string", nullable=true)
     */
    protected ?string $linkTarget = LinkEntityInterface::TARGET_TOP;

    /**
     * @ORM\Column(name="link_title", type="string", length=255, nullable=true)
     */
    protected ?string $linkTitle = null;

    /**
     * @ORM\Column(name="no_follow", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $noFollow = false;

    public function isNoFollow(): bool
    {
        return $this->noFollow;
    }

    //////////////////////////////////////////////////////////
    // methods getters and setters

    public function getLinkType(): ?string
    {
        return $this->linkType;
    }

    public function setLinkType(?string $linkType): static
    {
        $this->linkType = $linkType;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getLinkPage(): ?AbstractPage
    {
        return $this->linkPage;
    }

    public function setLinkPage(?AbstractPage $linkPage): static
    {
        $this->linkPage = $linkPage;

        return $this;
    }

    public function getLinkTarget(): ?string
    {
        return $this->linkTarget;
    }

    public function setLinkTarget(?string $linkTarget): static
    {
        $this->linkTarget = $linkTarget;

        return $this;
    }

    public function getLinkTitle(): ?string
    {
        return $this->linkTitle;
    }

    public function setLinkTitle(?string $linkTitle): static
    {
        $this->linkTitle = $linkTitle;

        return $this;
    }

    public function getNoFollow(): bool
    {
        return $this->noFollow;
    }

    public function setNoFollow(bool $noFollow): static
    {
        $this->noFollow = $noFollow;

        return $this;
    }
}
