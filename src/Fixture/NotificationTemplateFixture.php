<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Fixture;

use LWC\SyliusNotifierPlugin\Entity\NotificationTemplateInterface;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class NotificationTemplateFixture extends AbstractResourceFixture
{
    public function getName(): string
    {
        return 'lwc_sylius_notifier_notification_template';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        /** @var NodeBuilder $node */
        $node = $resourceNode->children();

        $node->scalarNode('code');
        $node->scalarNode('channel')->defaultValue(NotificationTemplateInterface::CHANNEL_TYPE_EMAIL);
        $node->scalarNode('type')->defaultValue(NotificationTemplateInterface::TYPE_SIMPLE);
        $node->scalarNode('from')->cannotBeEmpty();
        $node->variableNode('translations')->cannotBeEmpty()->defaultValue([]);
        $node->booleanNode('enabled')->defaultTrue();
    }
}
