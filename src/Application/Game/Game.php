<?php

namespace Application\Game;

use Silex\Application;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Game
{
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
