<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TileRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    
    /**
     * @return array
     */
    public function getByCoordinatesRange(array $coordinatesRange = array())
    {
        $start = current($coordinatesRange);
        $end = end($coordinatesRange);
        
        return $this->createQueryBuilder('t')
            ->where('t.coordinatesX >= ?1 AND
                t.coordinatesX <= ?2 AND
                t.coordinatesY >= ?1 AND
                t.coordinatesY <= ?2')
            ->setParameter(1, $start)
            ->setParameter(2, $end)
            ->getQuery()
            ->getResult()
        ;
    }
}
