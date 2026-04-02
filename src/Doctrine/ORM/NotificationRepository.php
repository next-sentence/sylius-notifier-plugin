<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Doctrine\ORM;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository implements NotificationRepositoryInterface
{
    public function getActiveNotification(bool $isLoggedIn = false): array
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->andWhere('o.enabled = :enabled')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('o.startDate'),
                    $qb->expr()->lt('o.startDate', ':now'),
                ),
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('o.endDate'),
                    $qb->expr()->gte('o.endDate', ':now'),
                ),
            )
            ->setParameter('now', (new \DateTimeImmutable())->format('Y-m-d H:i:s'))
            ->setParameter('enabled', true)
            ->addOrderBy('o.startDate', 'DESC')
            ->addOrderBy('o.priority', 'DESC')
        ;

        if (!$isLoggedIn) {
            $qb->andWhere('o.onlyLoggedInUsers = :onlyLoggedInUsers')
                ->setParameter('onlyLoggedInUsers', false);
        }

        return $qb->getQuery()->getResult();
    }
}
