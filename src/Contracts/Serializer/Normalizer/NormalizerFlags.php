<?php

declare(strict_types=1);

namespace App\Contracts\Serializer\Normalizer;

abstract class NormalizerFlags
{
    public const FORMAT_DTO    = 'dto';
    public const FORMAT_ENTITY = 'entity';
}
