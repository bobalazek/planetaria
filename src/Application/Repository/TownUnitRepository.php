<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownUnitRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('tu')
            ->select('COUNT(tu.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
