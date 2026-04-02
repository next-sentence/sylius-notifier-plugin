<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity\Traits;

trait IdentifiableTrait
{
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
