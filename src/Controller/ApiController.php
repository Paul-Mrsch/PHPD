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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function new(EntityManagerInterface $entityManager, SerializerInterface $serializerinterface, Request $request, ValidatorInterface $validator)
    {
        $category = $serializerinterface->deserialize($request->getContent(), Category::class, 'json');

        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Category created!'], Response::HTTP_CREATED);
    }

    #[Route('/api/category/edit/{id}', name: 'api_category_edit', methods: ['PUT'])]
    public function edit(EntityManagerInterface $entityManager, SerializerInterface $serializerinterface, Request $request, ValidatorInterface $validator, CategoryRepository $categoryRepository, $id)
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();

        $serializerinterface->deserialize($data, Category::class, 'json', ['object_to_populate' => $category]);

        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Category updated!'], Response::HTTP_OK);
    }
}
