<?php

namespace App\Controller;

use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
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
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        $seasons = $seasonRepository->findBy(['program' => $program]);

        return $this->render(
            'program/show.html.twig', [
                'id' => $id,
                'program' => $program,
                'seasons' => $seasons,
            ]
        );
    }

    #[Route('/{programId}/seasons/{seasonId}', methods:['get'], requirements: ['programId' => '\d+', 'seasonId' => '\d+'], name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $program = $programRepository->find($programId);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$programId.' found in program\'s table.'
            );
        }

        $seasons = $seasonRepository->find($seasonId);

        if (!$seasons) {
            throw $this->createNotFoundException(
                'No season with id : '.$seasonId.' found in season\'s table.'
            );
        }

        $episodes = $episodeRepository->findBy(['season' => $seasons]);

        return $this->render(
            'program/season_show.html.twig', [
                'program' => $program, 
                'seasons' => $seasons, 
                'episodes' => $episodes
            ]
        );
    }

    #[Route('/{else}', methods:['get'], requirements: ['else' => '.*'], name: 'notFound')]
    public function notFound(): Response
    {
        return $this->render('program/notFound.html.twig', [
        ]);
    }
}
