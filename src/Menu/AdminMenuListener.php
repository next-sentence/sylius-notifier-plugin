<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Menu;

use Knp\Menu\Util\MenuManipulator;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function __construct(
        private readonly MenuManipulator $menuManipulator,
    ) {
    }

    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $notifierMenu = $menu
            ->addChild('notification')
            ->setLabel('lwc_sylius_notifier.ui.notifications')
        ;

        if (null !== $marketingMenu = $menu->getChild('marketing')) {
            $marketingMenuPos = array_search($marketingMenu, array_values($menu->getChildren()), true);
            $this->menuManipulator->moveToPosition(
                $notifierMenu,
                false === $marketingMenuPos ? 5 : $marketingMenuPos + 1,
            );
        }

        $notifierMenu
            ->addChild('notifications', [
                'route' => 'lwc_sylius_notifier_admin_notification_index',
            ])
            ->setExtra('routes', [
                'lwc_sylius_notifier_admin_notification_create',
                'lwc_sylius_notifier_admin_notification_index',
                'lwc_sylius_notifier_admin_notification_update',
            ])
            ->setLabel('lwc_sylius_notifier.menu.notifications')
            ->setLabelAttribute('icon', 'envelope')
        ;

        $notifierMenu
            ->addChild('notification_template', [
                'route' => 'lwc_sylius_notifier_admin_notification_template_index',
            ])
            ->setExtra('routes', [
                'lwc_sylius_notifier_admin_notification_template_create_by_channel',
                'lwc_sylius_notifier_admin_notification_template_index',
                'lwc_sylius_notifier_admin_notification_template_update',
            ])
            ->setLabel('lwc_sylius_notifier.menu.notification_templates')
            ->setLabelAttribute('icon', 'envelope')
        ;
    }
}
