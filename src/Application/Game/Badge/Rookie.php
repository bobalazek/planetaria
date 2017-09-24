<?php

namespace Application\Game\Badge;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Rookie extends AbstractBadge
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Rookie')
            ->setKey('rookie')
            ->setDescription('You are just getting started.')
            ->setMinimumExperiencePoints(100)
        ;
    }
}
