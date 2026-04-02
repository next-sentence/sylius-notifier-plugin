<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Doctrine\ORM;

use Sylius\Component\Resource\Repository\RepositoryInterface;

interface NotificationRepositoryInterface extends RepositoryInterface
{
    public function getActiveNotification(bool $isLoggedIn = false): array;
}
