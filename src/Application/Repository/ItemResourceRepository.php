<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ItemResourceRepository extends EntityRepository
{
    public function countAll()
    {
        return $this->createQueryBuilder('ir')
            ->select('COUNT(ir.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
