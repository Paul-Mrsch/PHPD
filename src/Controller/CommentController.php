<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/api/comment/index', name: 'app_comment_index')]
    public function index(CommentRepository $commentRepository,  SerializerInterface $serializerinterface): JsonResponse
    {
        $comments = $commentRepository->findAll();

        $commentsSerialized = $serializerinterface->serialize($comments, 'json');

        return new JsonResponse($commentsSerialized);
    }
}
