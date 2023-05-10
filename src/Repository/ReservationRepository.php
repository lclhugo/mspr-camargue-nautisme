<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


//method that return a list of available equipments at a given location for a given date
    public function findAvailableEquipments($location, $dateLocation): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Equipment e
            WHERE e.rentalLocation = :location
            AND e.id NOT IN (
                SELECT r.equipment
                FROM App\Entity\Reservation r
                WHERE r.dateLocation = :dateLocation
            )
            ORDER BY e.name ASC'
        )->setParameter('location', $location)

            ->setParameter('dateLocation', $dateLocation);

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findAvailableEquipmentsByDateAndLocation($getDate, $getId)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Equipment e
            WHERE e.rentalLocation = :location
            AND e.id NOT IN (
                SELECT r.equipment
                FROM App\Entity\Reservation r
                WHERE r.dateLocation = :dateLocation
            )
            ORDER BY e.name ASC'
        )->setParameter('location', $getId)

            ->setParameter('dateLocation', $getDate);

        // returns an array of Product objects
        return $query->getResult();
    }
}