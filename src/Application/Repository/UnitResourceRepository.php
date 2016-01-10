<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UnitResourceRepository extends EntityRepository
{
    public function countAll()
    {
        return $this->createQueryBuilder('ur')
            ->select('COUNT(ur.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
