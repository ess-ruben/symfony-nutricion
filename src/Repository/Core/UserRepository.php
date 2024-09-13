<?php

namespace App\Repository\Core;

use App\Entity\Core\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\PDO\PgSQL\Driver as PDOPgSqlDriver;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param string $role
     * @return User[]
     */
    public function findByRole(string $role): array
    {
        $qb = $this->getQueryBuilderByRoleLike($role);

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    public function getQueryBuilderByRoleLike(string $role)
    {
        $qb = $this->createQueryBuilder('u');

        if ($this->isPostgreSQL()) {
            // Es PostgreSQL
            $qb
                ->andWhere('JSON_GET_TEXT(u.roles, 0) = :role')
                ->setParameter('role',$role)
            ;
        }else{
            $qb
                ->andWhere($qb->expr()->like('u.roles',':role'))
                ->setParameter('role', "%$role%")
            ;
        }

        return $qb;
    }

    public function setQueryByRole(QueryBuilder $qb,string $role,$alias = 'u')
    {
        if ($this->isPostgreSQL()) {
            // Es PostgreSQL
            $qb
                ->andWhere('JSON_GET_TEXT(u.roles, 0) = :role')
                ->setParameter('role',$role)
            ;
        }else{
            $qb
                ->andWhere($qb->expr()->like("$alias.roles",':role'))
                ->setParameter('role', "%$role%")
            ;
        }
    }

    public function getQueryBuilderByRoleNotLike(string $role)
    {
        $qb = $this->createQueryBuilder('u');

        if ($this->isPostgreSQL()) {
            // Es PostgreSQL
            $qb
                ->andWhere('JSON_GET_TEXT(u.roles, 0) <> :role')
                ->setParameter('role',$role)
            ;
        }else{
            $qb
                ->andWhere($qb->expr()->notLike('u.roles',':role'))
                ->setParameter('role', "%$role%")
            ;
        }

        return $qb;
    }

    private function isPostgreSQL() : bool
    {
        //$connection = $this->getEntityManager()->getConnection();

        // Obtener el driver actual de la conexión
        //$driver = $connection->getDriver();

        return true;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
