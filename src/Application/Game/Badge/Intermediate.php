<?php

namespace Application\Game\Badge;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Intermediate extends AbstractBadge
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Intermediate')
            ->setKey('intermediate')
            ->setDescription('That is the spirit.')
            ->setMinimumExperiencePoints(500)
        ;
    }
}
