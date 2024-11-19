<?php

namespace App\Controller;

use App\Entity\Watch;
use App\Entity\Rack;
use App\Form\WatchType;
use App\Repository\WatchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FileUploadError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/watch')]
final class WatchController extends AbstractController
{
    #[Route(name: 'app_watch_index', methods: ['GET'])]
    public function index(WatchRepository $watchRepository): Response
    {
        return $this->render('watch/index.html.twig', [
            'watches' => $watchRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_watch_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Rack $rack): Response
    {
        $watch = new Watch();
        $watch->setRack($rack);
        $form = $this->createForm(WatchType::class, $watch);
        $form->handleRequest($request);
        
        $valid=false;
        $submitted=$form->isSubmitted();
        dump($submitted);
        if($submitted) {
            $valid=$form->isValid();
            dump($valid);
        }
        
        if ($submitted && $valid) {
                    
            $entityManager->persist($watch);
            $entityManager->flush();
            
            $this->addFlash('Watch', 'bien ajoutée');
            
            return $this->redirectToRoute('rack_show',
                ['id' => $rack ->getId()],
                Response::HTTP_SEE_OTHER);
        }
        
        // now, we know invalid data was submitted
        if(! $valid) {
            // check all errors from all of the form's fields (in depth)
            $errors = $form->getErrors(true);
            
            foreach($errors as $error) {
                dump($error);
                
                // now, if we find a problem of file upload, propagate the error into the top-level form
                if($error instanceof FileUploadError) {
                    $form->addError($error);
                }
            }
        }

        return $this->render('watch/new.html.twig', [
            'watch' => $watch,
            'rack' => $rack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_watch_show', methods: ['GET'])]
    public function show(Watch $watch): Response
    {
        return $this->render('watch/show.html.twig', [
            'watch' => $watch,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_watch_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Watch $watch, EntityManagerInterface $entityManager): Response
    {
        $rack = $watch->getRack();
        $form = $this->createForm(WatchType::class, $watch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('rack_show', ['id' => $rack->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('watch/edit.html.twig', [
            'watch' => $watch,
            'rack' => $rack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_watch_delete', methods: ['POST'])]
    public function delete(Request $request, Watch $watch, EntityManagerInterface $entityManager,): Response
    {   
        $rack = $watch->getRack();
        if ($this->isCsrfTokenValid('delete'.$watch->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($watch);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rack_show', ['id' => $rack->getId()], Response::HTTP_SEE_OTHER);
    }
}
