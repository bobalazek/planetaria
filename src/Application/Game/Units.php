<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Units
{
    /**
     * @var string
     */
    const RIFLEMAN = 'rifleman';

    /**
     * @var string
     */
    const GRENADIER = 'grenadier';

    /**
     * @var string
     */
    const BAZOOKA_SOLDIER = 'bazooka_soldier';

    /**
     * @var string
     */
    const LEOPARD_2 = 'leopard_2';

    /**
     * @var string
     */
    const M1_ABRAMS = 'm1_abrams';

    /**
     * @var string
     */
    const CHALLENGER_2 = 'challenger_2';

    /**
     * @var string
     */
    const F22 = 'f22';

    /**
     * @var string
     */
    const B2 = 'b2';

    /**
     * @var string
     */
    const APACHE = 'apache';

    /**
     * @var string
     */
    const DESTROYER = 'destroyer';

    /**
     * @var string
     */
    const SUBMARINE = 'submarine';

    /**
     * @var string
     */
    const AIRCRAFT_CARRIER = 'aircraft_carrier';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::RIFLEMAN => 'Rifleman',
            self::GRENADIER => 'Grenadier',
            self::BAZOOKA_SOLDIER => 'Bazooka soldier',
            self::LEOPARD_2 => 'Leopard 2',
            self::M1_ABRAMS => 'M1 abrams',
            self::CHALLENGER_2 => 'Challenger 2',
            self::F22 => 'F22',
            self::B2 => 'B2',
            self::APACHE => 'Apache',
            self::DESTROYER => 'Destroyer',
            self::SUBMARINE => 'Submarine',
            self::AIRCRAFT_CARRIER => 'Aircraft carrier',
        );
    }
}
