<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingResourceRepository extends EntityRepository
{
    public function countAll()
    {
        return $this->createQueryBuilder('br')
            ->select('COUNT(br.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
