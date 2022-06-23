<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MemberGroup;
use App\Form\MemberGroupType;
use App\Repository\MemberRepository;
use App\Security\Voter\MemberGroupVoter;
use App\Repository\MemberGroupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/group', name: 'group_')]
class MemberGroupController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'User tried to access a page without having ROLE_SUPER_ADMIN')]
    public function create(MemberGroupRepository $memberGroupRepository, Request $request): Response
    {
        $group = new MemberGroup();

        $form = $this->createForm(MemberGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberGroupRepository->add($group);
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('member_group/create.html.twig', [
            'form'  => $form
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'User tried to access a page without having ROLE_SUPER_ADMIN')]
    public function edit(MemberGroup $group, MemberGroupRepository $memberGroupRepository, Request $request): Response
    {
        $form = $this->createForm(MemberGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberGroupRepository->add($group);
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('member_group/edit.html.twig', [
            'group' => $group,
            'form'  => $form
        ]);
    }

    #[Route('/{id<\d+>}/remove/member', name: 'remove_member', methods: ['POST'])]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'User tried to access a page without having ROLE_SUPER_ADMIN')]
    public function removeMember(
        MemberGroup $group, 
        MemberGroupRepository $memberGroupRepository, 
        MemberRepository $memberRepository,
        Request $request
    ): Response {
        $memberId = $request->request->getInt('member');
        $member = $memberRepository->find($memberId);

        if (!$member) {
            throw $this->createNotFoundException(
                sprintf('No Member found for id %s', $memberId)
            );
        }

        $group->removeMember($member);
        $memberGroupRepository->add($group);

        return $this->redirectToRoute('app_home');
    }

    #[Route('/{id<\d+>}/delete', methods: ['POST'], name: 'delete')]
    #[IsGranted(MemberGroupVoter::DELETE, subject: 'group')]
    public function delete(MemberGroup $group, Request $request, MemberGroupRepository $memberGroupRepository): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('app_home');
        }

        $memberGroupRepository->remove($group);

        return $this->redirectToRoute('app_home');
    }
}
