<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNewByUser($userId): ?Subscription
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.status = :status')
            ->setParameter('status', Subscription::STATUS_NEW)
            ->andWhere("s.user_id = :user_id")
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getNotPaidSubscriptions()
    {
        return $this->createQueryBuilder('s')
            ->select("s")
            ->leftJoin("App:SubscriptionPayment", "sp", Join::WITH, "s.id = sp.subscription")
            ->andWhere("sp.date > :date")
            ->setParameter('date', new \DateTime("-37 day"))
            ->having("sp is null")
            ->getQuery()
            ->getResult()
            ;
    }
}
