<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\MemberGroup;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MemberGroupVoter extends Voter
{
    public const DELETE = 'member_group_can_edit';

    public function __construct(private Security $security)
    {}

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::DELETE])
            && $subject instanceof MemberGroup;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var MemberGroup $group */
        $group = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::DELETE:
                return $this->security->isGranted('ROLE_SUPER_ADMIN') && $group->getMembers()->count() === 0;
                break;
        }

        return false;
    }
}
