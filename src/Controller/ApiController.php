<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{
    public function index()
    {
        return new JsonResponse('homepage');
    }
}