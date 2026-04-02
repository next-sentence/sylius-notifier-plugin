<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Form\Type\Translation;

use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\RichEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationTemplateTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.ui.name',
                'required' => true,
            ])
            ->add('subject', TextType::class, [
                'label' => 'app.ui.subject',
            ])
            ->add('body', RichEditorType::class, [
                'label' => 'app.ui.body',
                'required' => false,
            ])
        ;
    }
}
