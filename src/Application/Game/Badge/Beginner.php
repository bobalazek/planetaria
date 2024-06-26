<?php

namespace Application\Game\Badge;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Beginner extends AbstractBadge
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Beginner')
            ->setKey('beginner')
            ->setDescription('This is just the begining.')
            ->setMinimumExperiencePoints(200)
        ;
    }
}
