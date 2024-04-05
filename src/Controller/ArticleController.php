<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleController extends AbstractController
{
    #[Route('/api/articles/index', name: 'app_article')]
    public function index(ArticleRepository $articleRepository,  SerializerInterface $serializerinterface): JsonResponse
    {
        $articles = $articleRepository->findAll();

        $articlesSerialized = $serializerinterface->serialize($articles, 'json');

        return new JsonResponse($articlesSerialized);
    }

    #[Route('/api/article/show/{id}', name: 'app_article_show')]
    public function show(ArticleRepository $articleRepository, SerializerInterface $serializerinterface, $id): JsonResponse
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            return new JsonResponse(['error' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        $article = $serializerinterface->serialize($article, 'json');

        return new JsonResponse($article);
    }

    #[Route('/api/article/new', name: 'app_article_new')]
    public function new(EntityManagerInterface $entityManager, SerializerInterface $serializerinterface, Request $request, ValidatorInterface $validator)
    {
        $article = $serializerinterface->deserialize($request->getContent(), Article::class, 'json');

        $errors = $validator->validate($article);

        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($article);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Article created!'], Response::HTTP_CREATED);
    }

    #[Route('/api/article/edit/{id}', name: 'app_article_edit')]
    public function edit(EntityManagerInterface $entityManager, SerializerInterface $serializerinterface, Request $request, ValidatorInterface $validator, $id)
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['error' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        $serializerinterface->deserialize($request->getContent(), Article::class, 'json', ['object_to_populate' => $article]);

        $errors = $validator->validate($article);

        if (count($errors) > 0) {
            $errorString = (string) $errors;
            return new JsonResponse(['error' => $errorString], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Article updated!'], Response::HTTP_OK);
    }

    #[Route('/api/article/delete/{id}', name: 'app_article_delete')]
    public function delete(EntityManagerInterface $entityManager, $id)
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['error' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Article deleted!'], Response::HTTP_OK);
    }
}
