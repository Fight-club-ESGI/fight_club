<?php

namespace App\Repository;

use App\Entity\TicketEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TicketEvent>
 *
 * @method TicketEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketEvent[]    findAll()
 * @method TicketEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketEvent::class);
    }

    public function save(TicketEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TicketEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getIsActiveTicketEventById(string $id): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.isActive = :val')
            ->setParameter('val', true)
            ->andWhere('e.event = :id')
            ->setParameter('id', $id)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return TicketEvent[] Returns an array of TicketEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TicketEvent
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
