<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Wood extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Wood')
            ->setKey('wood')
            ->setDescription('Used primarily for buildings.')
        ;
    }
}
