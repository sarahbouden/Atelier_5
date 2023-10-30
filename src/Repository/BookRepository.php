<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
    public function SearchByref($id){
        return $this->createQueryBuilder('b')
        ->where('b.ref LIKE :ref')
        ->setParameter('ref',$id)
        ->getQuery()
        ->getResult();
    }
    public function orderByauthor(){
        
        $em = $this->getEntityManager();
        return $em->createQueryBuilder()
        ->select('b')
        ->from(Book::class, 'b')
        ->join('b.Author', 'a')
        ->orderBy('a.Username','ASC')
        ->getQuery()
        ->getResult();
    }
public function orderByYearAndNbr()
    {
        
        $em = $this->getEntityManager();
        return $em->createQueryBuilder()
        ->select('b')
        ->from(Book::class, 'b')
        ->join('b.Author', 'a')
        ->where('b.public_date < :before')
        ->andWhere('a.nbrlivre > :nbr')
        ->setParameter('before', 2023)
        ->setParameter('nbr', 35)
        ->getQuery()
        ->getResult();
    }
    public function nbrBooksSC(){
        $em=$this->getEntityManager();
        return $em->createQuery('SELECT count(p)FROM App\Entity\Book b WHERE b.Category:=SC')
        ->setParameter('SC','Science Fiction')
        ->getResult();
    }
//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
