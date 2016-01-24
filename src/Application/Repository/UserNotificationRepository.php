<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserNotificationRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('un')
            ->select('COUNT(un.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
