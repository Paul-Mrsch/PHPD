<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/category/index', name: 'api_category_index', methods: ['GET'])]
    public function api(categoryRepository $categoryRepository,  SerializerInterface $serializerinterface, $id): JsonResponse
    {
        $category = $categoryRepository->findAll();

        $categories = $serializerinterface->serialize($category, 'json');

        return new JsonResponse($categories);
    }

    #[Route('/api/category/show/{id}', name: 'show', methods: ['GET'])]
    public function category(categoryRepository $categoryRepository,  SerializerInterface $serializerinterface, $id): JsonResponse
    {
        $category = $categoryRepository->find($id);

        $categories = $serializerinterface->serialize($category, 'json');

        return new JsonResponse($categories);
    }
}
