<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserBadgeRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('ub')
            ->select('COUNT(ub.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
