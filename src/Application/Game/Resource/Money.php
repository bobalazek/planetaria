<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Money extends AbstractResource
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Money')
            ->setKey('money')
            ->setSlug('money')
            ->setDescription('Used primarily for buildings.')
        ;
    }
}
