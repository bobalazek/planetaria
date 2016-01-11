<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TileRepository extends EntityRepository
{
    public function countAll()
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
