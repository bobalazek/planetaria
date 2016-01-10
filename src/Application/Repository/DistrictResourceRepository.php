<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class DistrictResourceRepository extends EntityRepository
{
    public function countAll()
    {
        return $this->createQueryBuilder('dr')
            ->select('COUNT(dr.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
