<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Mailer;

use Sylius\Component\Core\Model\OrderInterface;

interface OrderEmailManagerInterface extends \Sylius\Bundle\CoreBundle\Mailer\OrderEmailManagerInterface
{
    public function sendConfirmationEmail(OrderInterface $order): void;

    public function getOrderEmailData(OrderInterface $order): array;
}
