<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        
        return $this->render(
            'program/index.html.twig',
            compact('programs')
        );
    }

    #[Route('/{id}', methods:['get'], requirements: ['id' => '\d+'], name: 'show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        return $this->render(
            'program/show.html.twig',
            compact('program')
        );
    }

    #[Route('/{else}', methods:['get'], requirements: ['else' => '.*'], name: 'notFound')]
    public function notFound(): Response
    {
        return $this->render('program/notFound.html.twig', [
        ]);
    }
}
