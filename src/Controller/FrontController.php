<?php

namespace App\Controller;

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

        #[Route('/home', name: 'homeName')]
        public function home() : response
        {
            $message = 'Home';
            #dd($message);
            return $this->render('/front/home.html.twig', [
                'messageTwig' => $message
            ]);
        }
}
