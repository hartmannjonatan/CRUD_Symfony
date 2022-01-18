<?php

namespace App\Repository;

use App\Entity\Postagem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Postagem|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postagem|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postagem[]    findAll()
 * @method Postagem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostagemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postagem::class);
    }

    public function findAllPostagens(?int $id = 0): array  
    {
        $conn = $this->getEntityManager()->getConnection();
        if($id == 0){
            $sql = '
                SELECT p.titulo, p.descricao, p.created_at, p.author, p.id, p.slug, p.tag, categoria.name
                FROM postagem as p
                INNER JOIN categoria ON p.categoria_id_id = categoria.id
                ORDER BY p.id DESC;
            ';
        } else {
            $sql = '
                SELECT p.titulo, p.descricao, p.created_at, p.author, p.id, p.slug, p.tag, categoria.name
                FROM postagem as p
                INNER JOIN categoria ON p.categoria_id_id = categoria.id
                WHERE p.id = '.$id.'
                ORDER BY p.id DESC;
            ';
        }
        
        $stmt = $conn->executeQuery($sql)->fetchAll();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt;
    }

    public function findAllByCategoria(int $idCat): array  
    {
        $conn = $this->getEntityManager()->getConnection();
            
        $sql = '
            SELECT p.titulo, p.descricao, p.created_at, p.author, p.id, p.slug, p.tag
            FROM postagem as p
            WHERE p.categoria_id_id = '.$idCat.'
            ORDER BY p.id DESC;
        ';

        $stmt = $conn->executeQuery($sql)->fetchAll();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt;
    }


    public function findSlug(string $slug){

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT p.titulo, p.descricao, p.conteudo, p.created_at, p.updated_at, p.author, p.id, p.slug, p.tag, categoria.name
        FROM postagem as p
        INNER JOIN categoria ON p.categoria_id_id = categoria.id
        WHERE p.slug = "'.$slug.'";
        ';

        $stmt = $conn->executeQuery($sql)->fetchAll();
        return $stmt;
    }

    // /**
    //  * @return Postagem[] Returns an array of Postagem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Postagem
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
