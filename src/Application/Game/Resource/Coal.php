<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Coal extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Coal')
            ->setKey('coal')
            ->setSlug('coal')
            ->setDescription('Used primarily for buildings.')
        ;
    }
}
