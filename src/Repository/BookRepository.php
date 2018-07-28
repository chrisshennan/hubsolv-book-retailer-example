<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param array $filters
     * @return Book[] Returns an array of Book objects
     */
    public function findByFilters(array $filters = [])
    {
        $filters = array_filter($filters);

        $queryBuilder = $this->createQueryBuilder('b');

        if (isset($filters['author'])) {
            $queryBuilder
                ->innerJoin('b.Author', 'a')
                ->andWhere('a.name = :author')
                ->setParameter('author', $filters['author']);
        }

        if (isset($filters['category'])) {
            $queryBuilder
                ->innerJoin('b.Category', 'c')
                ->andWhere('c.title = :category')
                ->setParameter('category', $filters['category']);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
