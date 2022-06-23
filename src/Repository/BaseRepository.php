<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AbstractEntity $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AbstractEntity $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }
}
