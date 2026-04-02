<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Mailer;

use LWC\SyliusNotifierPlugin\Message\CreateNotification;
use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Bundle\CoreBundle\Mailer\ShipmentEmailManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class ShipmentEmailManager implements ShipmentEmailManagerInterface
{
    public function __construct(
        private readonly OrderEmailManagerInterface $orderEmailManager,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function sendConfirmationEmail(ShipmentInterface $shipment): void
    {
        /** @var OrderInterface $order */
        $order = $shipment->getOrder();
        $email = $order->getCustomer()->getEmail();
        Assert::notNull($email);

        $this->messageBus->dispatch(new CreateNotification(
            Emails::SHIPMENT_CONFIRMATION_RESENT,
            $email,
            array_merge([
                '%TRACKING_CODE%' => $shipment->getTracking(),
            ], $this->orderEmailManager->getOrderEmailData($order)),
        ));
    }

    public function resendConfirmationEmail(ShipmentInterface $shipment): void
    {
        /** @var OrderInterface $order */
        $order = $shipment->getOrder();
        $email = $order->getCustomer()->getEmail();
        Assert::notNull($email);

        $this->messageBus->dispatch(new CreateNotification(
            Emails::SHIPMENT_CONFIRMATION,
            $email,
            array_merge([
                '%TRACKING_CODE%' => $shipment->getTracking(),
            ], $this->orderEmailManager->getOrderEmailData($order)),
        ));
    }
}
