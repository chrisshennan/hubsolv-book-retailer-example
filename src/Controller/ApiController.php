<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Manager\BookManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var BookManager
     */
    private $bookManager;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * ApiController constructor.
     * @param EntityManagerInterface $entityManager
     * @param BookManager $bookManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, BookManager $bookManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->bookManager = $bookManager;
        $this->validator = $validator;
    }

    /**
     * Get a list of books
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bookList(Request $request)
    {
        $filters = [
            'category' => $request->get('category'),
            'author' => $request->get('author'),
        ];

        $books = $this->entityManager->getRepository(Book::class)
            ->findByFilters($filters);

        return new JsonResponse($this->generateOutput($books));
    }

    public function bookCreate(Request $request)
    {

        $data = [
            'isbn' => $request->get('isbn'),
            'title' => $request->get('title'),
            'author' => $request->get('author'),
            'category' => $request->get('category'),
            'price' => $request->get('price'),
        ];

        $book = $this->bookManager->create($data);

        $errors = $this->validator->validate($book);

        if(count($errors)) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage()
                ];
            }

            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return new JsonResponse($this->generateOutput([$book]), JsonResponse::HTTP_CREATED);
        }
    }

    /**
     * Get a list of categories
     * @param Request $request
     * @return JsonResponse
     */
    public function categoryList(Request $request)
    {
        $categories = $this->entityManager->getRepository(Category::class)
            ->findAll();

        return new JsonResponse($this->generateOutput($categories));
    }

    /**
     * Format the output as per the spec on http://jsonapi.org
     * @param array $dataCollection
     * @return array
     */
    protected function generateOutput(array $dataCollection)
    {
        $included = [];

        foreach ($dataCollection as $dataItem) {
            $included = array_merge($included, $dataItem->getIncludedRelationships());
        }

        $data = [
            'data' => $dataCollection,
        ];

        if (count($included)) {
            $data['included'] = $included;
        }

        return $data;
    }
}