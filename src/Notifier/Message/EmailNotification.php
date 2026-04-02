<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Notifier\Message;

use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class EmailNotification extends Notification implements EmailNotificationInterface
{
    private string $from = '';

    private string $subject = '';

    private array $data = [];

    private ?string $body = null;

    public function __construct(
        array $data = [],
        ?string $body = null,
        ?string $subject = '',
    ) {
        $this->data = $data;
        $this->body = $body;
        $this->subject = $subject ?? '';
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        return ['email'];
    }

    public function asEmailMessage(EmailRecipientInterface $recipient, ?string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        $message->getMessage()
            ->from($this->getFrom())
            ->htmlTemplate('@LWCSyliusNotifierPlugin/Email/dynamic.html.twig')
            ->subject($this->getSubject())
            ->context([
                'body' => $this->getBody(),
                'data' => $this->getData(),
            ])
        ;

        return $message;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject ?? '';

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(?string $from): self
    {
        $this->from = $from ?? '';

        return $this;
    }
}
