<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Rock extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Rock')
            ->setKey('rock')
            ->setDescription('Used primarily for buildings.')
        ;
    }
}
