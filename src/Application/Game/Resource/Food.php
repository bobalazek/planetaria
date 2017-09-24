<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Food extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Food')
            ->setKey('food')
            ->setDescription('Used to feed the units and maybe for buildings production.')
        ;
    }
}
