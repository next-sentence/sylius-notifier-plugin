<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Form\Type;

use LWC\SyliusNotifierPlugin\Form\Type\Translation\NotificationTemplateTranslationType;
use LWC\SyliusNotifierPlugin\Entity\NotificationTemplate;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationTemplateType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $notificationTemplate = $builder->getData();

        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius.ui.code',
                'disabled' => $notificationTemplate->getId() ?? false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.form.user.enabled',
                'required' => false,
            ])
            ->add('from', TextType::class, [
                'label' => 'app.ui.from',
            ])
            ->add('type', TextType::class, [
                'label' => 'app.ui.type',
                'disabled' => true,
            ])
            ->add('channel', ChoiceType::class, [
                'choices' => NotificationTemplate::getChannelTypes(),
                'label' => 'lwc_sylius_notifier.ui.channel',
                'disabled' => $notificationTemplate->getId() ?? false,
                'required' => true,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => NotificationTemplateTranslationType::class,
                'label' => 'sylius.ui.translations',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'lwc_sylius_notifier_notification_template';
    }
}
