<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TileResourceRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('tr')
            ->select('COUNT(tr.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
