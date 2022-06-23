<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MemberGroupRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: '`member_groups`')]
#[ORM\Entity(repositoryClass: MemberGroupRepository::class)]
#[
    ApiResource(
        collectionOperations: [
            'get',
            'post' => [
                'security' => "is_granted('ROLE_SUPER_ADMIN')",
                'openapi_context' => [
                    'security' => [
                        ['bearerAuth' => []]
                    ]
                ]
            ]
        ],
        itemOperations: [
            'get',
            'patch' => [
                'security' => "is_granted('ROLE_SUPER_ADMIN')",
                'openapi_context' => [
                    'security' => [
                        ['bearerAuth' => []]
                    ]
                ]
            ],
            'put' => [
                'security' => "is_granted('ROLE_SUPER_ADMIN')",
                'openapi_context' => [
                    'security' => [
                        ['bearerAuth' => []]
                    ]
                ]
            ],
            'delete' => [
                'security' => "is_granted('ROLE_SUPER_ADMIN')",
                'openapi_context' => [
                    'security' => [
                        ['bearerAuth' => []]
                    ]
                ]
            ]
        ]
    )
]
class MemberGroup extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    /**
     * @var Member[]|Collection
     */
    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: 'memberGroups', cascade: ['persist'])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->getMembers()->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        $this->getMembers()->removeElement($member);

        return $this;
    }
}
