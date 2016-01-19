<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Electricity extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Electricity')
            ->setKey('electricity')
            ->setSlug('electricity')
            ->setDescription('Used primarily for buildings.')
        ;
    }
}
