<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use Symfony\Component\Serializer\Serializer;

class ApiController extends AbstractController
{
    #[Route('/api/category/index', name: 'api_category_index', methods: ['GET'])]
    public function api(categoryRepository $categoryRepository,  SerializerInterface $serializerinterface): JsonResponse
    {
        $category = $categoryRepository->findAll();

        $categories = $serializerinterface->serialize($category, 'json');

        return new JsonResponse($categories);
    }

    #[Route('/api/category/show/{id}', name: 'api_category_show', methods: ['GET'])]
    public function category(categoryRepository $categoryRepository,  SerializerInterface $serializerinterface, $id): JsonResponse
    {

        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $categories = $serializerinterface->serialize($category, 'json');

        return new JsonResponse($categories);
    }

    #[Route('/api/category/new', name: 'api_category_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager, SerializerInterface $serializerinterface, Request $request)
    {
        $category = $serializerinterface->deserialize($request->getContent(), Category::class, 'json');

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Category created!'], Response::HTTP_CREATED);
    }
}
