<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MemberGroup;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberGroup>
 *
 * @method MemberGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberGroup[]    findAll()
 * @method MemberGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberGroupRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberGroup::class);
    }
}
