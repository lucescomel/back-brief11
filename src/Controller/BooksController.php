<?php
// src\Controller\BookController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    #[Route('/api/books', name: 'book', methods: ['GET'])]
    public function getBookList(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'welcome to your new controller!',
            'path' => 'src/Controller/BookController.php',
        ]);
    }
}