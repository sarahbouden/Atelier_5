<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }
    public function orderByusername(){
        return $this->createQueryBuilder('a')
        ->orderBy('a.Username','Desc')
        ->getQuery()
        ->getResult();
    }
    public function orderByemail(){
        return $this->createQueryBuilder('a')
        ->orderBy('a.email','ASC')
        ->getQuery()
        ->getResult();
    }
    public function SearchByalpha(){
        return $this->createQueryBuilder('a')
        ->where('a.Username LIKE :name')
        ->setParameter('name','%a%')
        ->getQuery()
        ->getResult();
    }
    public function SearchAllStudentsFirstname(){
        return $this->createQueryBuilder('a')
        ->where('a.Username LIKE :name')
        ->andWhere('a.email LIKE :email')
        ->setParameters(['name'=>'a%','email'=>'%a%'])
        ->getQuery()
        ->getResult();
    }
    public function SearchById($id){
        return $this->createQueryBuilder('a')
        ->join('a.Books','b')
        ->addSelect('b')
        ->where('b.Author=:id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult();
    }
    public function searchUsername($Username){
        return $this->createQueryBuilder('a')
        ->where('a.Username=:name')
        ->setParameter('name',$Username)
        ->getQuery()
        ->getResult();   
    }
    public function minmax($min,$max){
        $em=$this->getEntityManager();
        return $em->createQuery('SELECT From App\Entity\Author a WHERE a.nbrlivre BETWEEN ?1 and :max')
        ->setParameters(['1'=>$min,'max'=>$max])
        ->getResult();
    }
//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
