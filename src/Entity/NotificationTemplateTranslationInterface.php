<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface NotificationTemplateTranslationInterface extends ResourceInterface, TranslationInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getSubject(): ?string;

    public function setSubject(?string $subject): void;

    public function getBody(): ?string;

    public function setBody(?string $body): void;
}
