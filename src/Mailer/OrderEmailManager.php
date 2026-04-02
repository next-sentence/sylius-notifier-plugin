<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Mailer;

use LWC\SyliusNotifierPlugin\Message\CreateNotification;
use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class OrderEmailManager implements OrderEmailManagerInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function sendConfirmationEmail(OrderInterface $order): void
    {
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();
        $email = $customer->getEmail();
        Assert::notNull($email);

        $this->messageBus->dispatch(new CreateNotification(
            Emails::ORDER_CONFIRMATION,
            $email,
            $this->getOrderEmailData($order),
        ));
    }

    public function resendConfirmationEmail(OrderInterface $order): void
    {
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();
        $email = $customer->getEmail();
        Assert::notNull($email);

        $this->messageBus->dispatch(new CreateNotification(
            Emails::ORDER_CONFIRMATION_RESENT,
            $email,
            $this->getOrderEmailData($order),
        ));
    }

    public function getOrderEmailData(OrderInterface $order): array
    {
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        return [
            '%LOCALE_CODE%' => $order->getLocaleCode(),
            '%FIRST_NAME%' => $customer->getFirstName(),
            '%LAST_NAME%' => $customer->getLastName(),
            '%EMAIL%' => $customer->getEmail(),
            '%TOKEN_VALUE%' => $order->getTokenValue(),
            '%ORDER_NUMBER%' => $order->getNumber(),
        ];
    }
}
