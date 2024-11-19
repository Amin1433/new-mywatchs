<?php

namespace App\Controller;

use App\Entity\Member;
use App\Repository\MemberRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MemberController extends AbstractController
{
    #[Route('/member', name: 'app_member')]
    public function index(MemberRepository $memberRepository)
    {  $members = $memberRepository->findAll();
    
    dump($members);
    
    return $this->render('member/index.html.twig',
        [ 'members' => $members ]
        );
    }


    
    
    #[Route('/member/{id}', name: 'member_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {
        $memberRepo = $doctrine->getRepository(Member::class);
        $member = $memberRepo->find($id);
        $galeries = $member -> getGaleries();
        
        return $this->render('member/show.html.twig',
            [ 'member'=> $member,
            'galeries' => $galeries],
            );
    }
}
