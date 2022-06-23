<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController
{
    public const PROFILE_PATH = '/profile';

    public function __construct(private Security $security)
    {}

    public function __invoke(): ?UserInterface
    {
        $user = $this->security->getUser();
        return $user;
    }
}
