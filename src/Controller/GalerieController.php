<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Entity\Watch;
use App\Entity\Member;
use App\Form\GalerieType;
use App\Repository\GalerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/galerie')]
final class GalerieController extends AbstractController
{
    #[Route(name: 'app_galerie_index', methods: ['GET'])]
    public function index(GalerieRepository $galerieRepository): Response
    {
        return $this->render('galerie/index.html.twig', [
            'galeries' => $galerieRepository->findBy(['publiee' => true]),
        ]);
    }

    #[Route('/new/{id}', name: 'app_galerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,  Member $member): Response
    {
        $galerie = new Galerie();
        $galerie -> setCreator($member);
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($galerie);
            $entityManager->flush();
            
            $this->addFlash('Galerie', 'bien créee');
            
            return $this->redirectToRoute('member_show', ['id' => $galerie -> getCreator()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('galerie/new.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_galerie_show', methods: ['GET'])]
    public function show(Galerie $galerie): Response
    {
        return $this->render('galerie/show.html.twig', [
            'galerie' => $galerie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_galerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Galerie $galerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('member_show', ['id' => $galerie -> getCreator()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('galerie/edit.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_galerie_delete', methods: ['POST'])]
    public function delete(Request $request, Galerie $galerie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$galerie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($galerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('member_show', ['id' => $galerie -> getCreator()->getId()], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/watch/{id}', name: 'app_galerie_watch_show', requirements: ['id' => '\d+'])]
    public function watchShow(
        #[MapEntity(id: 'galerie_id')]
        Galerie $galerie,
        #[MapEntity(id: 'watch_id')]
        Watch $watch
        ): Response
        {
            // Vérification : la montre appartient-elle à la galerie ?
            if (! $galerie->getWatches()->contains($watch)) {
                throw $this->createNotFoundException("Cette montre n'appartient pas à cette galerie.");
            }
            
            // Vérification : la galerie est-elle publiée ?
            if (! $galerie->isPublished()) {
                throw $this->createAccessDeniedException("Vous n'avez pas accès à cette ressource.");
            }
            
            return $this->render('watch/show.html.twig',
               [ 'watch' => $watch, 'galerie' => $galerie ]);
    }
}
