<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MemberGroupRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(MemberGroupRepository $memberGroupRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'groups' => $memberGroupRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }
}
