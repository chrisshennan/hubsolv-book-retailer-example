<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SampleFixtures
 * @package App\DataFixtures
 */
class SampleFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categories = $this->createCategories($manager);
        $authors = $this->createAuthors($manager);
        $books = $this->createBooks($manager, $categories, $authors);

        // Save the entities into the database
        $manager->flush();
    }

    /**
     * @return array
     */
    protected function createCategories($manager)
    {
        $categoryTitles = ['PHP', 'Javascript', 'Linux'];

        $categories = [];
        foreach ($categoryTitles as $categoryTitle) {
            $category = new Category();
            $category->setTitle($categoryTitle);

            // Index by title for convenience for assigning to books later
            $categories[$category->getTitle()] = $category;
        }

        // Run this in a separate loop for readability
        foreach ($categories as $category) {
            $manager->persist($category);
        }

        return $categories;
    }

    /**
     * @param ObjectManager $manager
     * @return array
     */
    protected function createAuthors(ObjectManager $manager)
    {
        $authorNames = ['Robin Nixon', 'Christopher Negus', 'Douglas Crockford'];

        $authors = [];
        foreach ($authorNames as $authorName) {
            $author = new Author();
            $author->setName($authorName);

            // Index by name for convenience for assigning to books later
            $authors[$author->getName()] = $author;
        }

        // Run this in a separate loop for readability
        foreach ($authors as $author) {
            $manager->persist($author);
        }

        return $authors;
    }

    /**
     * @param ObjectManager $manager
     * @param array $categories
     * @param $authors
     * @return array
     */
    protected function createBooks(ObjectManager $manager, array $categories, $authors)
    {
        $books = [];

        $book = new Book();
        $book->setIsbn('978-1491918661');
        $book->setTitle('Learning PHP, MySQL & JavaScript: With jQuery, CSS & HTML5');
        $book->setAuthor($authors['Robin Nixon']);
        $book->setPrice(9.99);
        $book->addCategory($categories['PHP']);
        $book->addCategory($categories['Javascript']);
        $books[] = $book;

        $book = new Book();
        $book->setIsbn('978-0596804848');
        $book->setTitle('Ubuntu: Up and Running: A Power User\'s Desktop Guide');
        $book->setAuthor($authors['Robin Nixon']);
        $book->setPrice(12.99);
        $book->addCategory($categories['Linux']);
        $books[] = $book;

        $book = new Book();
        $book->setIsbn('978-1118999875');
        $book->setTitle('Linux Bible');
        $book->setAuthor($authors['Christopher Negus']);
        $book->setPrice(19.99);
        $book->addCategory($categories['Linux']);
        $books[] = $book;

        $book = new Book();
        $book->setIsbn('978-0596517748');
        $book->setTitle('JavaScript: The Good Parts');
        $book->setAuthor($authors['Douglas Crockford']);
        $book->setPrice(8.99);
        $book->addCategory($categories['Javascript']);
        $books[] = $book;

        foreach ($books as $book) {
            $manager->persist($book);
        }

        return $books;
    }
}