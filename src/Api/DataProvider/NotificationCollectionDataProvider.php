<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Api\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use LWC\SyliusNotifierPlugin\Doctrine\ORM\NotificationRepositoryInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;

final class NotificationCollectionDataProvider implements ProviderInterface
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly ShopperContextInterface $shopperContext,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $customer = $this->shopperContext->getCustomer();
        $isLoggedIn = $customer !== null;

        return $this->notificationRepository->getActiveNotification($isLoggedIn);
    }
}
