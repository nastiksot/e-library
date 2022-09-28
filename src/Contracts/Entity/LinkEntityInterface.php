<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use App\Entity\Page\AbstractPage;

interface LinkEntityInterface
{
    public const TARGET_TOP   = '_top';
    public const TARGET_BLANK = '_blank';

    public const LINK_TYPE_PAGE          = 'page';
    public const LINK_TYPE_EXTERNAL_LINK = 'link';

    public function getLinkType(): ?string;

    public function getLinkPage(): ?AbstractPage;

    public function getLink(): ?string;

    public function getLinkTarget(): ?string;

    public function getLinkTitle(): ?string;

    public function isNoFollow(): bool;
}
