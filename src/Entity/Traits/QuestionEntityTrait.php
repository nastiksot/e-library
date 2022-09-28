<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait QuestionEntityTrait
{
    /**
     * @ORM\Column(name="question", type="string", length=255, nullable=true)
     */
    protected ?string $question = null;

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }
}
