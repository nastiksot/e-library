<?php

declare(strict_types=1);

namespace App\Service\Mail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use function array_merge;

class TemplatedEmailFactory
{
    public function createEmail(
        string $view,
        Address|string $from,
        Address|string $to,
        ?string $subject,
        ?string $message = null,
        ?array $context = [],
    ): TemplatedEmail {
        return (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($view)
            ->context(array_merge(['subject' => $subject, 'message' => $message], $context));
    }
}
