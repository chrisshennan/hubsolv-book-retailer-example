<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function books(Request $request)
    {
        $filters = [
            'category' => $request->get('category'),
            'author' => $request->get('author'),
        ];

        $books = $this->entityManager->getRepository(Book::class)
            ->findByFilters($filters);

        return new JsonResponse($this->generateOutput($books));
    }

    public function categories(Request $request)
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