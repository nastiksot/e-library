<?php

declare(strict_types=1);

namespace App\Entity\Traits\Contact;

use function array_filter;
use function implode;

trait FullNameEntityTrait
{
    use FirstNameEntityTrait;
    use LastNameEntityTrait;

    public function getFullName(): ?string
    {
        return implode(' ', array_filter([$this->getFirstName(), $this->getLastName(), null, '']));
    }
}
