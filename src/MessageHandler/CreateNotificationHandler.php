<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\MessageHandler;

use LWC\SyliusNotifierPlugin\Message\CreateNotification;
use LWC\SyliusNotifierPlugin\Entity\NotificationTemplateInterface;
use LWC\SyliusNotifierPlugin\Notifier\Message\EmailNotification;
use Psr\Log\LoggerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

#[AsMessageHandler]
class CreateNotificationHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly NotifierInterface $notifier,
        private readonly RepositoryInterface $notificationTemplateRepository,
    ) {
    }

    public function __invoke(CreateNotification $notificationMessage): void
    {
        try {
            $template = $this->notificationTemplateRepository->findOneBy([
                'code' => $notificationMessage->getCode(),
                'enabled' => true,
            ]);

            if (null === $template) {
                $this->logger->warning(sprintf("Notification template with code '%s' doesn't exists!", $notificationMessage->getCode()));

                return;
            }

            $this->sendEmailNotification($template, $notificationMessage);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
        }
    }

    public function sendEmailNotification(
        NotificationTemplateInterface $template,
        CreateNotification $notificationMessage,
    ): void {
        $emailNotification = new EmailNotification();
        $recipient = new Recipient($notificationMessage->getRecipient());

        $emailNotification
            ->importance('')
            ->setSubject($template->getSubject())
            ->setBody($template->getBody())
            ->setFrom($template->getFrom())
            ->setData($notificationMessage->getData())
        ;

        $this->notifier->send($emailNotification, $recipient);
    }
}
