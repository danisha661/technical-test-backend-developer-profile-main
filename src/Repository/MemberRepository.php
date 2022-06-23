<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Member;
use App\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 *
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function getMembers(int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('members')
            ->addSelect('memberGroups')
            ->leftJoin('members.memberGroups', 'memberGroups')
            ->orderBy('members.createdAt', 'DESC')
        ;

        return (new Paginator($queryBuilder))->paginate($page);
    }
}
