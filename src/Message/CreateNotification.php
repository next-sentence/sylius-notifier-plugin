<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Message;

final class CreateNotification
{
    private string $code;

    private string $recipient;

    private array $data;

    public function __construct(string $code, string $recipient, array $data = [])
    {
        $this->code = $code;
        $this->recipient = $recipient;
        $this->data = $data;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
