<?php

namespace App\Controller;

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

        #[Route('', name: 'homeName')]
        public function home(categoryRepository $categoryRepository) : response
        {


            $categoriesController = $categoryRepository->findAll();

            dump($categoriesController);


            return $this->render('/front/home.html.twig', [
                'titleTwig' => 'Listes des catégories',
                'categoriesTwig' => $categoriesController,
            ]);
        }
}
