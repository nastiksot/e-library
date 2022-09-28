<?php

declare(strict_types=1);

namespace App\Controller\Api\UserDealer;

use App\Controller\AbstractController;

class AbstractUserDealerController extends AbstractController
{
    protected function checkPermissions(string $attribute, mixed $subject, int $dealerId): void
    {
        $this->denyAccessUnlessGranted($attribute, [
            'object'             => $subject,
            'dealerIdBasedOnUid' => $dealerId,
        ]);
    }
}
