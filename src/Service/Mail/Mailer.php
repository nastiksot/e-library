<?php

declare(strict_types=1);

namespace App\Service\Mail;

use App\CQ\Command\MailLog\CreateMailLogCommand;
use App\Entity\MailLog;
use App\Service\MessageBusHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;
use Throwable;
use function date;
use function sprintf;
use function uniqid;

class Mailer
{
    public function __construct(
        private TransportInterface $transport,
        private MessageBusHandler $messageBusHandler,
        private bool $useLog = true,
    ) {
    }

    public function disableLog(): self
    {
        $this->useLog = false;

        return $this;
    }

    public function enableLog(): self
    {
        $this->useLog = true;

        return $this;
    }

    public function send(Email $email): void
    {
        try {
            $sentMessage = $this->transport->send($email);
            $status      = MailLog::STATUS_SENT;
            $debug       = $sentMessage ? $sentMessage->getDebug() : '';
        } catch (Throwable $e) {
            $sentMessage = null;
            $status      = MailLog::STATUS_ERROR;
            $debug       = $e->getMessage();
        }

        // after sent create mail log
        if ($this->useLog) {
            $this->createMailLog($status, $debug, $email, $sentMessage);
        }
    }

    private function generateId(): string
    {
        return sprintf('%s.%s', date('YmdHis'), uniqid('', true));
    }

    private function createMailLog(string $status, string $debug, Email $email, ?SentMessage $sent = null): void
    {
        if ($email instanceof TemplatedEmail) {
            $message = $email->getContext()['message'] ?? null;
        } elseif ($email->getHtmlBody()) {
            $message = $email->getHtmlBody();
        } else {
            $message = $email->getTextBody();
        }

        // create mail log
        try {
            $this->messageBusHandler->handleCommand(
                new CreateMailLogCommand(
                    $sent ? $sent->getMessageId() : $this->generateId(),
                    $status,
                    $debug,
                    $email->getSubject(),
                    $message,
                    $email->getFrom() ? $email->getFrom()[0]->toString() : null,
                    $email->getReplyTo() ? $email->getReplyTo()[0]->toString() : null,
                    $email->getTo() ? $email->getTo()[0]->toString() : null,
                    $email->getCc() ? $email->getCc()[0]->toString() : null,
                    $email->getBcc() ? $email->getBcc()[0]->toString() : null,
                )
            );
        } catch (Throwable) {
        }
    }
}
