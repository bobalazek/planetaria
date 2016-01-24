<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserMessageRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('um')
            ->select('COUNT(um.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
