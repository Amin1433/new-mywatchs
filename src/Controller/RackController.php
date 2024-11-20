<?php

namespace App\Controller;

use App\Entity\Rack;
use App\Repository\RackRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RackController extends AbstractController
{
    #[Route('/rack', name: 'app_rack')]
    public function index(): Response
    {
        return $this->render('rack/index.html.twig', [
            'controller_name' => 'RackController',
        ]);
    }
    
    
    #[Route('/rack/list', name: 'rack_list', methods: ['GET'])]
    public function list(RackRepository $rackRepository)
    {  $racks = $rackRepository->findAll();
    
    dump($racks);
    
    return $this->render('rack/list.html.twig',
        [ 'racks' => $racks ]
        );
    }
        
    
    #[Route('/rack/{id}', name: 'rack_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {   
        $rackRepo = $doctrine->getRepository(Rack::class);
        $rack = $rackRepo->find($id);
        
        // Vérification si le rack existe
        if (!$rack) {
            throw $this->createNotFoundException('Rack not found');
        }
        
        $member= $rack ->getMember();
        $user = $this->getUser();
        
        // Vérification si l'utilisateur connecté est le membre associé au rack
        if (!$user || $member === null || $member->getId() !== $user->getId()) {
            // Si ce n'est pas le même membre, accès refusé
            throw $this->createAccessDeniedException("Tu n'as pas le droit d'acceder au presentoir d'un autre membre");
        }
        
        return $this->render('rack/show.html.twig',
            [ 'rack'=> $rack ]
            );
    }
    
}
