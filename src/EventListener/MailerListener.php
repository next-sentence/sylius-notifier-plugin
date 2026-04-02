<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\EventListener;

use LWC\SyliusNotifierPlugin\Message\CreateNotification;
use Sylius\Bundle\CoreBundle\Mailer\Emails as CoreBundleEmails;
use Sylius\Bundle\UserBundle\Mailer\Emails as UserBundleEmails;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class MailerListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LocaleContextInterface $localeContext,
        private readonly RouterInterface $router,
    ) {
    }

    public function sendResetPasswordTokenEmail(GenericEvent $event): void
    {
        /** @var UserInterface $user */
        $user = $event->getSubject();

        $passwordResetUrl = $this->router->generate(
            'sylius_shop_password_reset',
            ['token' => $user->getPasswordResetToken()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        $this->sendEmail($user, UserBundleEmails::RESET_PASSWORD_TOKEN, [
            '%PASSWORD_RESET_TOKEN%' => $user->getPasswordResetToken(),
            '%PASSWORD_RESET_URL%' => $passwordResetUrl,
        ]);
    }

    public function sendResetPasswordPinEmail(GenericEvent $event): void
    {
        $this->sendEmail($event->getSubject(), UserBundleEmails::RESET_PASSWORD_PIN);
    }

    public function sendVerificationTokenEmail(GenericEvent $event): void
    {
        /** @var UserInterface $user */
        $user = $event->getSubject();

        $emailVerificationUrl = $this->router->generate(
            'sylius_shop_user_verification',
            ['token' => $user->getEmailVerificationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        $this->sendEmail($user, UserBundleEmails::EMAIL_VERIFICATION_TOKEN, [
            '%EMAIL_VERIFICATION_TOKEN%' => $user->getEmailVerificationToken(),
            '%EMAIL_VERIFICATION_URL%' => $emailVerificationUrl,
        ]);
    }

    public function sendUserRegistrationEmail(GenericEvent $event): void
    {
        $subject = $event->getSubject();

        if ($subject instanceof CustomerInterface) {
            $customer = $subject;
            $user = $customer->getUser();
        } elseif ($subject instanceof ShopUserInterface) {
            $user = $subject;
            $customer = $subject->getCustomer();
        } else {
            Assert::isInstanceOfAny($subject, [CustomerInterface::class, ShopUserInterface::class]);
        }

        if (null === $user) {
            return;
        }

        $email = $customer->getEmail();

        if (empty($email)) {
            return;
        }

        Assert::isInstanceOf($user, ShopUserInterface::class);

        $this->sendEmail($user, CoreBundleEmails::USER_REGISTRATION, [
            '%FIRST_NAME%' => $customer->getFirstName(),
            '%LAST_NAME%' => $customer->getLastName(),
            '%EMAIL%' => $email,
        ]);
    }

    private function sendEmail(UserInterface $user, string $emailCode, array $emailData = []): void
    {
        $email = $user->getEmail();
        Assert::notNull($email);

        $data = [
            '%LOCALE_CODE%' => $this->localeContext->getLocaleCode(),
            '%USERNAME%' => $user->getUsernameCanonical(),
        ];

        $this->messageBus->dispatch(
            new CreateNotification($emailCode, $email, array_merge($data, $emailData)),
        );
    }
}
