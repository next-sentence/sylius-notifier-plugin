<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface NotificationTemplateInterface extends
    ResourceInterface,
    CodeAwareInterface,
    TranslatableInterface
{
    public const TYPE_SIMPLE = 'simple';
    public const TYPE_CUSTOM = 'custom';

    public const CHANNEL_TYPE_IN_APP = 'in_app';
    public const CHANNEL_TYPE_EMAIL = 'email';
    public const CHANNEL_TYPE_SMS = 'sms';

    public function getType(): string;

    public function setType(string $type): void;

    public function getFrom(): ?string;

    public function setFrom(?string $from): void;

    public function getChannel(): ?string;

    public function setChannel(?string $channel): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getSubject(): ?string;

    public function setSubject(?string $subject): void;

    public function getBody(): ?string;

    public function setBody(?string $body): void;
}
