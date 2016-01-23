<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function countAll()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return mixed
     */
    public function countRegistrationsToday()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.timeCreated >= :dateToday')
            ->setParameter(
                'dateToday',
                new \DateTime(
                    date("Y-m-d")
                ),
                \Doctrine\DBAL\Types\Type::DATE
            )
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return mixed
     */
    public function countRegistrationsLast24Hours()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.timeCreated >= :dateLast24Hours')
            ->setParameter(
                'dateLast24Hours',
                new \DateTime(
                    'now - 24 hours'
                ),
                \Doctrine\DBAL\Types\Type::DATE
            )
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param $days
     *
     * @return mixed
     */
    public function getRegistrationsByLastDays($days = 7)
    {
        $results = array();

        $databaseResults = $this->createQueryBuilder('u')
            ->select('DATE(u.timeCreated) AS date, COUNT(u.id) AS countNumber') // count is an reseved keyword, that's why I used "counter"
            ->orderBy('u.timeCreated', 'DESC')
            ->groupBy('date')
            ->setMaxResults($days)
            ->getQuery()
            ->getResult()
        ;

        $firstDate = date('Y-m-d', strtotime('-'.$days.' days'));
        $lastDate = date('Y-m-d');
        $dates = dateRange($firstDate, $lastDate);

        foreach ($dates as $date) {
            $count = 0;

            if ($databaseResults) {
                foreach ($databaseResults as $databaseResult) {
                    if ($databaseResult['date'] == $date) {
                        $count = $databaseResult['countNumber'];
                        break;
                    }
                }
            }

            $results[] = array(
                'date' => $date,
                'count' => $count,
            );
        }

        return $results;
    }

    /**
     * @return mixed
     */
    public function getActiveTheLast5MinutesCount()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.timeLastActive >= :dateMinus5Minutes')
            ->setParameter(
                'dateMinus5Minutes',
                new \DateTime(
                    'now - 5 minutes'
                ),
                \Doctrine\DBAL\Types\Type::DATE
            )
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return mixed
     */
    public function getActiveTheLast60MinutesCount()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.timeLastActive >= :dateMinus1Hour')
            ->setParameter(
                'dateMinus1Hour',
                new \DateTime(
                    'now - 1 hour'
                ),
                \Doctrine\DBAL\Types\Type::DATE
            )
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return mixed
     */
    public function getActiveTheLast24HoursCount()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.timeLastActive >= :dateMinus1Hour')
            ->setParameter(
                'dateMinus1Hour',
                new \DateTime(
                    'now - 1 day'
                ),
                \Doctrine\DBAL\Types\Type::DATE
            )
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
