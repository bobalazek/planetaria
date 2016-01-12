<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CountryUnitRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('cu')
            ->select('COUNT(cu.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
