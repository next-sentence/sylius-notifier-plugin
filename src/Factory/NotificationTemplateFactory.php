<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Factory;

use LWC\SyliusNotifierPlugin\Entity\NotificationTemplateInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class NotificationTemplateFactory implements NotificationTemplateFactoryInterface
{
    public function __construct(
        private readonly FactoryInterface $factory,
    ) {
    }

    public function createNew(): NotificationTemplateInterface
    {
        /** @var NotificationTemplateInterface $notificationTemplate */
        $notificationTemplate = $this->factory->createNew();

        return $notificationTemplate;
    }

    public function createNewByChannel(string $channel): NotificationTemplateInterface
    {
        /** @var NotificationTemplateInterface $notificationTemplate */
        $notificationTemplate = $this->factory->createNew();
        $notificationTemplate->setChannel(strtoupper($channel));

        return $notificationTemplate;
    }
}
