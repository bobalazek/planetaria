<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class PlanetRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
