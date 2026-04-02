<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface NotificationInterface extends
    ResourceInterface,
    CodeAwareInterface
{
    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getPriority(): int;

    public function setPriority(int $priority): void;

    public function getStartDate(): ?\DateTimeInterface;

    public function setStartDate(?\DateTimeInterface $startDate): void;

    public function getEndDate(): ?\DateTimeInterface;

    public function setEndDate(?\DateTimeInterface $endDate): void;

    public function isOnlyLoggedInUsers(): bool;

    public function setOnlyLoggedInUsers(bool $onlyLoggedInUsers): void;

    public function getBody(): ?string;

    public function setBody(?string $body): void;
}
