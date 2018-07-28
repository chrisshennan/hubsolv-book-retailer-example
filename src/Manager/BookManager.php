<?php

namespace App\Manager;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class BookManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create($data)
    {
        $book = new Book();
        $book->setIsbn($data['isbn']);
        $book->setTitle($data['title']);
        $book->setPrice($data['price']);

        // Try and find the category
        $category = $this->entityManager->getRepository(Category::class)->findOneBy([
            'title' => $data['category']
        ]);

        // Create the category if it is not found
        if (!$category) {
            $category = new Category();
            $category->setTitle($data['category']);
            $this->entityManager->persist($category);
        }

        $book->addCategory($category);

        // Try and get the author
        $author = $this->entityManager->getRepository(Author::class)->findOneBy([
            'name' => $data['author']
        ]);

        // Create the author if it is not found
        if (!$author) {
            $author = new Author();
            $author->setName($data['author']);
            $this->entityManager->persist($author);
        }

        $book->setAuthor($author);

        $this->entityManager->persist($book);

        $this->entityManager->flush();

        return $book;
    }
}