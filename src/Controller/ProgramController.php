<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program/', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('{id}', methods:['get'], requirements: ['id' => '\d+'], name: 'show')]
    public function show(int $id): Response
    {
        return $this->render('program/show.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('{else}', methods:['get'], requirements: ['else' => '.*'], name: 'notFound')]
    public function notFound(): Response
    {
        return $this->render('program/notFound.html.twig', [
        ]);
    }
}
