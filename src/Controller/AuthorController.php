<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorController extends AbstractController
{
    // #[Route('/author', name: 'app_author')]
    // public function index(): Response
    // {
    //     return $this->render('author/index.html.twig', [
    //         'controller_name' => 'AuthorController',
    //     ]);
    // }

    #[Route('/api/authors', name: 'author', methods: ['GET'])]
    public function getAllAuthor(AuthorRepository $authorRepository, SerializerInterface $serializer): JsonResponse
    {
        $authorsList = $authorRepository->findAll();

        $jsonAuthorList = $serializer->serialize($authorsList, 'json', ['groups' => 'getBooks']);
        return new JsonResponse($jsonAuthorList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/authors/{id}', name: 'detailAuthor', methods: ['GET'])]
    public function getDetailAuthor(Author $author, SerializerInterface $serializer): JsonResponse
    {
        $jsonAuthor = $serializer->serialize($author, 'json', ['groups' => 'getBooks']);
        return new JsonResponse($jsonAuthor, Response::HTTP_OK, [], true);
    }

    #[Route('/api/authors/{id}', name: 'deleteAuthor', methods: ['DELETE'])]
    public function deleteAuthor(Author $author, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($author);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/authors', name: "createAuthor", methods: ['POST'])]
    public function createAuthor(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator , UrlGeneratorInterface $urlGenerator): JsonResponse
    {

        $author = $serializer->deserialize($request->getContent(), Author::class, 'json');

         // On vérifie les erreurs
         $errors = $validator->validate($author);

         if ($errors->count() > 0) {
             return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
         }

        $em->persist($author);
        $em->flush();

        $jsonAuthor = $serializer->serialize($author, 'json', ['groups' => 'getBooks']);

        $location = $urlGenerator->generate('detailBook', ['id' => $author->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonAuthor, Response::HTTP_CREATED, ["Location" => $location], true);
    }


//     #[Route('/api/authors/{id}', name:"updateAuthor", methods:['PUT'])]

//     public function updateAuthor(Request $request, SerializerInterface $serializer, Author $currentBook, EntityManagerInterface $em, AuthorRepository $authorRepository): JsonResponse 
//     {
//         $updatedBook = $serializer->deserialize($request->getContent(), 
//                 Author::class, 
//                 'json', 
//                 [AbstractNormalizer::OBJECT_TO_POPULATE => $currentBook]);
//         $content = $request->toArray();
//         $idAuthor = $content['idAuthor'] ?? -1;
//         $updatedBook->setAuthor($authorRepository->find($idAuthor));
        
//         $em->persist($updatedBook);
//         $em->flush();
//         return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
//    }
}
