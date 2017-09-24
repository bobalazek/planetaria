<?php

namespace Application\Game\Badge;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Expert extends AbstractBadge
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Expert')
            ->setKey('expert')
            ->setDescription('You are the man!')
            ->setMinimumExperiencePoints(5000)
        ;
    }
}
