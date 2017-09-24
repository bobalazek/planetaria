<?php

namespace Application\Game\Badge;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Experienced extends AbstractBadge
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Experienced')
            ->setKey('experienced')
            ->setDescription('You are getting better and better.')
            ->setMinimumExperiencePoints(1000)
        ;
    }
}
