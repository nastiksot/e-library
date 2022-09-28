<?php

declare(strict_types=1);

namespace App\Entity\Traits\Page;

use Doctrine\ORM\Mapping as ORM;

trait CodeBlockEntityTrait
{
    /**
     * @ORM\Column(name="code_block_active", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $codeBlockActive = false;

    /**
     * @ORM\Column(name="code_block", type="text", nullable=true)
     */
    protected ?string $codeBlock = null;

    public function isCodeBlockActive(): bool
    {
        return $this->codeBlockActive;
    }

    public function setCodeBlockActive(bool $codeBlockActive): self
    {
        $this->codeBlockActive = $codeBlockActive;

        return $this;
    }

    public function getCodeBlock(): ?string
    {
        return $this->codeBlock;
    }

    public function setCodeBlock(?string $codeBlock): self
    {
        $this->codeBlock = $codeBlock;

        return $this;
    }
}
