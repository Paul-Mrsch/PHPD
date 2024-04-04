<?php

namespace App\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
        #[Route('/catalogue', name: 'catalogueName')]
        public function front() : response
        {
            $message = 'Bonjour';
            #dd($message);
            return $this->render('/front/index.html.twig', [
                'messageTwig' => $message
            ]);
        }

        #[Route('', name: 'homeName', methods: ['GET'])]
        public function home(categoryRepository $categoryRepository, SerializerInterface $serializerinterface) : JsonResponse
        {


            $categoriesController = $categoryRepository->findAll();

            dump($categoriesController);

            $categories = $serializerinterface->serialize($categoriesController, 'json');

            // $array = [];

            // foreach ($categoriesController as $category) {
            //     $array[] = [
            //         'id' => $category->getId(),
            //         'name' => $category->getName(),
            //         'description' => $category->getDescription(),
            //     ];
            // }
            // return $this->render('/front/home.html.twig', [
            //     'titleTwig' => 'Listes des catégories',
            //     'categoriesTwig' => $categoriesController,
            // ]);

            return new JsonResponse($categories);
        }

        #[Route('/fiche_category/{id}', name: 'categoryName', methods: ['GET'])]
        public function category(categoryRepository $categoryRepository,  SerializerInterface $serializerinterface, $id) : JsonResponse
        {
            $category = $categoryRepository->find($id);

            $categories = $serializerinterface->serialize($category, 'json');

            return new JsonResponse($categories);
        }
}
