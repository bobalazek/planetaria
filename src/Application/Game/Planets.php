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
     * @param string $name
     * @param string $slug
     * @param string $description
     *
     * @return void
     */
    public function generateNew($name, $slug, $description)
    {
        $app = $this->app;

        // Planet
        $planetEntity = new PlanetEntity();
        $planetEntity
            ->setId(1)
            ->setName($name)
            ->setSlug($slug)
            ->setDescription($description)
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
                    ->setStatus(TileStatuses::ORIGINAL)
                    ->setBackgroundImage($terrain['backgroundImage'])
                    ->setCoordinatesX($x)
                    ->setCoordinatesY($y)
                ;

                $app['orm.em']->persist($tileEntity);

                // Tile resources
                $randomNumberOfResources = rand(1, 4);
                $resources = Resources::getAllForTiles();
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
        
        $app['orm.em']->flush();
    }
}
