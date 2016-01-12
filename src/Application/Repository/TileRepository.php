<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\PlanetEntity;

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
    public function getByCoordinatesRange(array $coordinatesRangeX = array(), array $coordinatesRangeY = array(), PlanetEntity $planet)
    {
        $startX = current($coordinatesRangeX);
        $endX = end($coordinatesRangeX);
        $startY = current($coordinatesRangeY);
        $endY = end($coordinatesRangeY);

        return $this->createQueryBuilder('t')
            ->where(
                't.coordinatesX >= ?1 AND
                t.coordinatesX <= ?2 AND
                t.coordinatesY >= ?3 AND
                t.coordinatesY <= ?4 AND
                t.planet = ?5'
            )
            ->setParameter(1, $startX)
            ->setParameter(2, $endX)
            ->setParameter(3, $startY)
            ->setParameter(4, $endY)
            ->setParameter(5, $planet)
            ->getQuery()
            ->getResult()
        ;
    }
}
