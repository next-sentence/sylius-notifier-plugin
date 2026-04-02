<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\DependencyInjection;

use LWC\SyliusNotifierPlugin\Doctrine\ORM\NotificationRepository;
use LWC\SyliusNotifierPlugin\Doctrine\ORM\NotificationTemplateRepository;
use LWC\SyliusNotifierPlugin\Form\Type\NotificationTemplateType;
use LWC\SyliusNotifierPlugin\Form\Type\NotificationType;
use LWC\SyliusNotifierPlugin\Form\Type\Translation\NotificationTemplateTranslationType;
use LWC\SyliusNotifierPlugin\Entity\Notification;
use LWC\SyliusNotifierPlugin\Entity\NotificationTemplate;
use LWC\SyliusNotifierPlugin\Entity\NotificationTemplateTranslation;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lwc_sylius_notifier');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;
        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $resourcesNode = $node
            ->children()
            ->arrayNode('resources')
            ->addDefaultsIfNotSet()
            ->children()
        ;

        $this->addNotificationTemplateResources($resourcesNode);
        $this->addNotificationResources($resourcesNode);
    }

    private function addNotificationTemplateResources(NodeBuilder $node): void
    {
        $node
            ->arrayNode('notification_template')
                ->addDefaultsIfNotSet()
                ->children()
                    ->variableNode('options')->end()
                    ->arrayNode('classes')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('model')->defaultValue(NotificationTemplate::class)->cannotBeEmpty()->end()
                            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                            ->scalarNode('factory')->defaultValue(Factory::class)->end()
                            ->scalarNode('repository')->defaultValue(NotificationTemplateRepository::class)->end()
                            ->scalarNode('form')->defaultValue(NotificationTemplateType::class)->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                    ->arrayNode('translation')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(NotificationTemplateTranslation::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->scalarNode('form')->defaultValue(NotificationTemplateTranslationType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addNotificationResources(NodeBuilder $node): void
    {
        $node
            ->arrayNode('notification')
                ->addDefaultsIfNotSet()
                ->children()
                    ->variableNode('options')->end()
                    ->arrayNode('classes')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('model')->defaultValue(Notification::class)->cannotBeEmpty()->end()
                            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                            ->scalarNode('factory')->defaultValue(Factory::class)->end()
                            ->scalarNode('repository')->defaultValue(NotificationRepository::class)->end()
                            ->scalarNode('form')->defaultValue(NotificationType::class)->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
