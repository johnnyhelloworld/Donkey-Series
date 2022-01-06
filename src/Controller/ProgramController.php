<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'new')]
    public function new(Request $request, ManagerRegistry $doctrine) : Response
    {
        // Create a new Category Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $doctrine->getManager();
            // For example : persiste & flush the entity
            $entityManager->persist($program);
            $entityManager->flush();
            // And redirect to a route that display the result
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/{id}', methods:['get'], requirements: ['id' => '\d+'], name: 'show')]
    public function show(Program $program, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $seasons = $seasonRepository->findBy(['program' => $program]);

        return $this->render(
            'program/show.html.twig', [
                'program' => $program,
                'seasons' => $seasons,
            ]
        );
    }

    #[Route('/{programId}/seasons/{seasonId}', methods:['get'], requirements: ['programId' => '\d+', 'seasonId' => '\d+'], name: 'season_show')]
    #[Entity('program', expr: 'repository.find(programId)')]
    #[Entity('season', expr: 'repository.find(seasonId)')]
    public function showSeason(Program $program, Season $season, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render(
            'program/season_show.html.twig', [
                'program' => $program, 
                'season' => $season, 
                'episodes' => $episodes
            ]
        );
    }

    #[Route('/{programId}/seasons/{seasonId}/episode/{episodeId}', methods:['get'], requirements: ['programId' => '\d+', 'seasonId' => '\d+', 'episodeId' => '\d+'], name: 'episode_show')]
    #[Entity('program', expr: 'repository.find(programId)')]
    #[Entity('season', expr: 'repository.find(seasonId)')]
    #[Entity('episode', expr: 'repository.find(episodeId)')]
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render(
            'program/episode_show.html.twig', [
                'program' => $program, 
                'season' => $season, 
                'episode' => $episode
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
