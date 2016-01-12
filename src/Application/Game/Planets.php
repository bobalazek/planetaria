<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\PlanetEntity;
use Application\Entity\TileEntity;
use Application\Entity\TileResourceEntity;

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
        $images = array(
            'grass1.png',
            'grass2.png',
            'grass3.png',
            'desert1.png',
            'desert2.png',
            'desert3.png',
        );

        foreach ($range as $x) {
            foreach ($range as $y) {
                $tileEntity = new TileEntity();

                $rand = array_rand($images);
                $backgroundImage = $x == 0 && $y == 0
                    ? 'ground-zero.png'
                    : $images[$rand]
                ;

                $tileEntity
                    ->setPlanet($planetEntity)
                    ->setTerrainType(TerrainTypes::GRASSLAND)
                    ->setBackgroundImage($backgroundImage)
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
    }
}
