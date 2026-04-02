<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Fixture\Factory;

use LWC\SyliusNotifierPlugin\Entity\NotificationTemplateInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationTemplateExampleFactory extends AbstractExampleFactory
{
    private OptionsResolver $optionsResolver;

    public function __construct(
        private readonly FactoryInterface $notificationTemplateFactory,
        private readonly RepositoryInterface $notificationTemplateRepository,
    ) {
        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): NotificationTemplateInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var NotificationTemplateInterface|null $notificationTemplate */
        $notificationTemplate = $this->notificationTemplateRepository->findOneBy([
            'code' => $options['code'],
        ]);

        if (null === $notificationTemplate) {
            $notificationTemplate = $this->notificationTemplateFactory->createNew();
        }

        $notificationTemplate->setEnabled($options['enabled']);
        $notificationTemplate->setChannel($options['channel']);
        $notificationTemplate->setCode($options['code']);
        $notificationTemplate->setType($options['type']);
        $notificationTemplate->setFrom($options['from']);

        if (null !== $notificationTemplate->getId()) {
            return $notificationTemplate;
        }

        foreach ($options['translations'] as $localeCode => $translationOptions) {
            $this->createTranslation($notificationTemplate, $localeCode, $translationOptions);
        }

        return $notificationTemplate;
    }

    protected function createTranslation(
        NotificationTemplateInterface $notificationTemplate,
        string $localeCode,
        array $options = [],
    ): void {
        $notificationTemplate->setCurrentLocale($localeCode);
        $notificationTemplate->setFallbackLocale($localeCode);

        $notificationTemplate->setName($options['name']);
        $notificationTemplate->setSubject($options['subject']);
        $notificationTemplate->setBody($options['body']);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode($options['name']);
            })
            ->setDefault('translations', [])
            ->setAllowedTypes('translations', ['array'])
            ->setDefault('enabled', true)
            ->setAllowedTypes('enabled', ['boolean'])
            ->setDefault('type', NotificationTemplateInterface::TYPE_SIMPLE)
            ->setAllowedTypes('type', ['string'])
            ->setDefault('from', null)
            ->setDefault('channel', NotificationTemplateInterface::CHANNEL_TYPE_EMAIL)
            ->setAllowedTypes('channel', ['string'])
        ;
    }
}
