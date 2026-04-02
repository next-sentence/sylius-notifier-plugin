<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity;

use LWC\SyliusNotifierPlugin\Entity\Traits\IdentifiableTrait;
use Sylius\Component\Resource\Model\AbstractTranslation;

class NotificationTemplateTranslation extends AbstractTranslation implements NotificationTemplateTranslationInterface
{
    use IdentifiableTrait;

    protected ?string $name = null;

    protected ?string $subject = null;

    protected ?string $body = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }
}
