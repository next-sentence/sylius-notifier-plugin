<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity\Traits;

use Symfony\Component\Uid\Uuid;

trait CodeAwareTrait
{
    protected ?string $code = null;

    public function initializeCode(): void
    {
        $this->code = Uuid::v4()->toRfc4122();
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }
}
