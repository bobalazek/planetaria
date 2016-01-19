<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class IronOre extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Iron ore')
            ->setKey('iron_ore')
            ->setSlug('iron-ore')
            ->setDescription('Used primarily for buildings.')
        ;
    }
}
