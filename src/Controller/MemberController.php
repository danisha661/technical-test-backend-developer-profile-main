<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/member', name: 'member_')]
class MemberController extends AbstractController
{
    #[Route('/', defaults: ['page' => '1'], methods: ['GET'], name: 'index')]
    #[Route('/page/{page<[1-9]\d*>}', methods: ['GET'], name: 'index_paginated')]
    public function index(int $page, MemberRepository $memberRepository): Response
    {
        return $this->renderForm('member/index.html.twig', [
            'paginator' => $memberRepository->getMembers($page),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'User tried to access a page without having ROLE_SUPER_ADMIN')]
    public function create(MemberRepository $memberRepository, Request $request): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberRepository->add($member);
            return $this->redirectToRoute('member_index');
        }

        return $this->renderForm('member/create.html.twig', [
            'member' => $member,
            'form'  => $form
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'User tried to access a page without having ROLE_SUPER_ADMIN')]
    public function edit(Member $member, MemberRepository $memberRepository, Request $request): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberRepository->add($member);
            return $this->redirectToRoute('member_index');
        }

        return $this->renderForm('member/edit.html.twig', [
            'member' => $member,
            'form'  => $form
        ]);
    }

    #[Route('/{id<\d+>}/delete', methods: ['POST'], name: 'delete')]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'User tried to access a page without having ROLE_SUPER_ADMIN')]
    public function delete(Member $member, Request $request, MemberRepository $memberRepository): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('member_index');
        }

        foreach ($member->getMemberGroups() as $group) {
            $group->removeMember($member);
        }

        $memberRepository->remove($member);

        return $this->redirectToRoute('member_index');
    }
}
