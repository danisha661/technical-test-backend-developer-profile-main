<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Member;
use App\Entity\MemberGroup;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i <= 5; $i++) {
            $name = $faker->company();
            
            $memberGroup = (new MemberGroup())
                ->setName($name)
                ->setDescription($faker->realText(100))
            ;

            $name = "";
            $manager->persist($memberGroup);
            $this->setReference("MemberGroup$i", $memberGroup);
        }

        for ($i = 0; $i <= 15; $i++) {
            /** @var MemberGroup|null $memberGroup */
            $memberGroup = $this->getReference('MemberGroup' . rand(0, 5));
            
            $member = (new Member())
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->addMemberGroup($memberGroup)
            ;

            $manager->persist($member);
            $this->setReference("Member$i", $member);
        }

        $admin = (new User())
            ->setEmail('john@admin.fr')
            ->setRoles(['ROLE_SUPER_ADMIN'])
        ;

        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $user = (new User())
            ->setEmail('john@user.fr')
            ->setRoles(['ROLE_USER'])
        ;

        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $manager->flush();
    }
}
