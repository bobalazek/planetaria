<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CrudeOil extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Crude oil')
            ->setKey('crude_oil')
            ->setSlug('crude-oil')
            ->setDescription('Used primarily for buildings.')
        ;
    }
}
