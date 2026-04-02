<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Entity;

use LWC\SyliusNotifierPlugin\Entity\Traits\CodeAwareTrait;
use LWC\SyliusNotifierPlugin\Entity\Traits\IdentifiableTrait;
use LWC\SyliusNotifierPlugin\Entity\Traits\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

class NotificationTemplate implements NotificationTemplateInterface
{
    use IdentifiableTrait;
    use CodeAwareTrait;
    use ToggleableTrait;
    use TimestampableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    protected string $type = self::TYPE_SIMPLE;

    protected string $channel = self::CHANNEL_TYPE_EMAIL;

    protected ?string $from = null;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->initializeTimestamp();
        $this->initializeCode();
    }

    public function __toString(): string
    {
        return $this->getType() ?? '';
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getSubject(): ?string
    {
        return $this->getTranslation()->getSubject();
    }

    public function setSubject(?string $subject): void
    {
        $this->getTranslation()->setSubject($subject);
    }

    public function getBody(): ?string
    {
        return $this->getTranslation()->getBody();
    }

    public function setBody(?string $body): void
    {
        $this->getTranslation()->setBody($body);
    }

    public static function getChannelTypes(): array
    {
        return [
            'lwc_sylius_notifier.ui.channel_email' => self::CHANNEL_TYPE_EMAIL,
            'lwc_sylius_notifier.ui.channel_sms' => self::CHANNEL_TYPE_SMS,
            'lwc_sylius_notifier.ui.channel_in_app' => self::CHANNEL_TYPE_IN_APP,
        ];
    }

    public function getTranslation(?string $locale = null): NotificationTemplateTranslationInterface
    {
        /** @var NotificationTemplateTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    protected function createTranslation(): NotificationTemplateTranslationInterface
    {
        return new NotificationTemplateTranslation();
    }
}
