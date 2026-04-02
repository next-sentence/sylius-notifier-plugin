<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity;

use LWC\SyliusNotifierPlugin\Entity\Traits\CodeAwareTrait;
use LWC\SyliusNotifierPlugin\Entity\Traits\IdentifiableTrait;
use LWC\SyliusNotifierPlugin\Entity\Traits\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Notification implements NotificationInterface
{
    use IdentifiableTrait;
    use CodeAwareTrait;
    use ToggleableTrait;
    use TimestampableTrait;

    protected ?string $description = null;

    protected int $priority = 0;

    protected ?\DateTimeInterface $startDate = null;

    protected ?\DateTimeInterface $endDate = null;

    protected bool $onlyLoggedInUsers = false;

    protected ?string $body = null;

    public function __construct()
    {
        $this->initializeTimestamp();
        $this->initializeCode();
    }

    public function __toString(): string
    {
        return $this->getCode() ?? '';
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function isOnlyLoggedInUsers(): bool
    {
        return $this->onlyLoggedInUsers;
    }

    public function setOnlyLoggedInUsers(bool $onlyLoggedInUsers): void
    {
        $this->onlyLoggedInUsers = $onlyLoggedInUsers;
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
