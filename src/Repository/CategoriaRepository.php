<?php

namespace App\Repository;

use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoria[]    findAll()
 * @method Categoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoria::class);
    }

    public function findAllCategory(?int $limit = 0): array  
    {
        $entityManager = $this->getEntityManager();

        if($limit == 0){
            $query = $entityManager->createQuery(
                'SELECT p.id, p.name, p.slug, p.createdAt
                FROM App\Entity\Categoria p
                ORDER BY p.id DESC'
            );
            $categoria = $query->getResult();
        } else {
            $query = $entityManager->createQuery(
                'SELECT p.id, p.name, p.slug, p.createdAt
                FROM App\Entity\Categoria p
                ORDER BY p.id DESC'
            );
            $categoria = $query->setMaxResults($limit)->getResult();
        }

        return $categoria;
    }

    public function findSlug(string $slug){

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT c.name, c.slug, c.id, c.created_at, c.updated_at
        FROM categoria c
        WHERE c.slug = "'.$slug.'";
        ';

        $stmt = $conn->executeQuery($sql)->fetchAll();
        return $stmt;
    }

    /**
    * @return Categoria[] Returns an array of Categoria objects
    */

    /*
    public function findOneBySomeField($value): ?Categoria
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
