<?php

namespace Application\Game\Badge;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Advanced extends AbstractBadge
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Advanced')
            ->setKey('advanced')
            ->setDescription('You are really good.')
            ->setMinimumExperiencePoints(2000)
        ;
    }
}
