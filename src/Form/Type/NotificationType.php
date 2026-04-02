<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Form\Type;

use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\RichEditorType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('enabled', CheckboxType::class, [
                'label' => 'lwc_sylius_notifier.ui.enabled',
                'required' => false,
            ])
            ->add('onlyLoggedInUsers', CheckboxType::class, [
                'label' => 'lwc_sylius_notifier.ui.only_logged_in_users',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'lwc_sylius_notifier.ui.description',
                'required' => false,
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'lwc_sylius_notifier.ui.starts_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'lwc_sylius_notifier.ui.ends_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'lwc_sylius_notifier.ui.priority',
                'required' => false,
            ])
            ->add('body', RichEditorType::class, [
                'label' => 'lwc_sylius_notifier.ui.body',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'lwc_sylius_notifier_notification';
    }
}
