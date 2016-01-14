<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\PlanetEntity;
use Application\Entity\TileEntity;
use Application\Entity\TileResourceEntity;
use Application\Entity\CountryEntity;
use Application\Entity\TownEntity;
use Application\Entity\UserCountryEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Planets
{
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return void
     */
    public function generateNew()
    {
        $app = $this->app;

        // Planet
        $planetEntity = new PlanetEntity();
        $planetEntity
            ->setId(1)
            ->setName('Earth')
            ->setSlug('earth')
            ->setDescription('The planet earth.')
        ;
        $app['orm.em']->persist($planetEntity);

        // Tiles
        $range = range(-32, 32);
        $terrains = array(
            array(
                'backgroundImage' => 'grassland/001.png',
                'type' => TerrainTypes::GRASSLAND,
            ),
            array(
                'backgroundImage' => 'grassland/002.png',
                'type' => TerrainTypes::GRASSLAND,
            ),
            array(
                'backgroundImage' => 'grassland/003.png',
                'type' => TerrainTypes::GRASSLAND,
            ),
            array(
                'backgroundImage' => 'desert/001.png',
                'type' => TerrainTypes::DESERT,
            ),
            array(
                'backgroundImage' => 'desert/002.png',
                'type' => TerrainTypes::DESERT,
            ),
            array(
                'backgroundImage' => 'desert/003.png',
                'type' => TerrainTypes::DESERT,
            ),
        );

        foreach ($range as $x) {
            foreach ($range as $y) {
                $tileEntity = new TileEntity();

                $randomIndex = array_rand($terrains);
                $terrain = $terrains[$randomIndex];

                $tileEntity
                    ->setPlanet($planetEntity)
                    ->setTerrainType($terrain['type'])
                    ->setBackgroundImage($terrain['backgroundImage'])
                    ->setCoordinatesX($x)
                    ->setCoordinatesY($y)
                ;

                $app['orm.em']->persist($tileEntity);

                // Tile resources
                $randomNumberOfResources = rand(1, 4);
                $resources = Resources::getAll();
                $randomResourceKeys = array_rand(
                    $resources,
                    $randomNumberOfResources
                );

                if (is_string($randomResourceKeys)) {
                    $randomResourceKeys = array(
                        $randomResourceKeys,
                    );
                }

                foreach ($randomResourceKeys as $randomResourceKey) {
                    $tileResourceEntity = new TileResourceEntity();

                    $amount = rand(5000, 20000);

                    $tileResourceEntity
                        ->setTile($tileEntity)
                        ->setResource($randomResourceKey)
                        ->setAmount($amount)
                        ->setAmountLeft($amount)
                    ;

                    $app['orm.em']->persist($tileResourceEntity);
                }
            }
        }

        // Country
        $countryEntity = new CountryEntity();
        $countryEntity
            ->setId(1)
            ->setName('Panem')
            ->setSlug('panem')
            ->setDescription('The country of all countries')
        ;
        $app['orm.em']->persist($countryEntity);

        // Town
        $townEntity = new TownEntity();
        $townEntity
            ->setId(1)
            ->setName('Panonia')
            ->setSlug('panonia')
            ->setDescription('The capital of Panem')
            ->setCountry($countryEntity)
        ;
        $app['orm.em']->persist($townEntity);

        // User country
        $userCountryEntity = new UserCountryEntity();
        $userCountryEntity
            ->setId(1)
            ->setRoles(array(
                CountryRoles::CREATOR,
                CountryRoles::OWNER,
            ))
            ->setCountry($countryEntity)
            ->setUser($app['orm.em']->find('Application\Entity\UserEntity', 1))
        ;
        $app['orm.em']->persist($userCountryEntity);

        // Save them, because we'll need them soon!
        $app['orm.em']->flush();

        // Town building - Capitol
        $app['game.buildings']->build(
            $townEntity,
            Buildings::CAPITOL,
            array(-1, -1) // Start (bottom left) coordinates
        );
        
        // Town building - Farms
        $farmsCoordinates = array(
            array(2, 2),
            array(2, -2),
            array(-2, -2),
            array(-2, 2),
        );
        foreach ($farmsCoordinates as $farmCoordinates)  {
            $app['game.buildings']->build(
                $townEntity,
                Buildings::FARM,
                $farmCoordinates
            );
        }
        
        // Town building - Houses
        $housesCoordinates = array(
            array(0, 2),
            array(2, 0),
            array(0, -2),
            array(-2, 0),
        );
        foreach ($housesCoordinates as $houseCoordinates)  {
            $app['game.buildings']->build(
                $townEntity,
                Buildings::HOUSE,
                $houseCoordinates
            );
        }
    }
}
