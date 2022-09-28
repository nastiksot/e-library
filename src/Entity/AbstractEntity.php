<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Entity\EntityInterface;
use App\Entity\Traits\BaseEntityTrait;
use App\Entity\Traits\Timestampable\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;

use function array_filter;
use function implode;
use function method_exists;

abstract class AbstractEntity implements EntityInterface
{
    use BaseEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        $labels = [];
        if (!$this->getId()) {
            $labels[] = 'New Entry';
        } else {
            $labels[] = 'Entry ID: ' . $this->getId();
            if (method_exists($this, 'isActive') && !$this->isActive()) {
                $labels[] = '(disabled)';
            }
        }

        return implode(' ', array_filter($labels));
    }

}
