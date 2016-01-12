<?php

namespace Application\Command\Database;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Entity\UserEntity;
use Application\Entity\ProfileEntity;
use Application\Entity\PlanetEntity;
use Application\Entity\TileEntity;
use Application\Entity\TileResourceEntity;
use Application\Entity\CountryEntity;
use Application\Entity\TownEntity;
use Application\Entity\UserCountryEntity;
use Application\Game\CountryRoles;
use Application\Game\Resources;
use Application\Game\TerrainTypes;
use Silex\Application;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class HydrateDataCommand extends ContainerAwareCommand
{
    protected $app;

    public function __construct($name, Application $app)
    {
        parent::__construct($name);

        $this->app = $app;
    }

    protected function configure()
    {
        $this
            ->setName(
                'application:database:hydrate-data'
            )
            ->setDescription('Add an Test User to the database')
            ->addOption(
                'remove-existing-data',
                'r',
                InputOption::VALUE_NONE,
                'When the existing data should be removed'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->app;

        $removeExistingData = $input->getOption('remove-existing-data');

        if ($removeExistingData) {
            try {
                $app['db']->query('SET foreign_key_checks = 0;');

                $tables = $app['db']->getSchemaManager()->listTables();

                foreach ($tables as $table) {
                    $table = $table->getName();

                    $app['db']->query('TRUNCATE TABLE '.$table.';');
                }

                $app['db']->query('SET foreign_key_checks = 1;');

                $output->writeln('<info>All tables were successfully truncated!</info>');
            } catch (\Exception $e) {
                $output->writeln('<error>'.$e->getMessage().'</error>');
            }
        }

        /***** Users *****/
        $users = include APP_DIR.'/fixtures/users.php';
        foreach ($users as $user) {
            $userEntity = new UserEntity();
            $profileEntity = new ProfileEntity();

            // Profile
            $profileEntity
                ->setFirstName($user['profile']['firstName'])
                ->setLastName($user['profile']['lastName'])
            ;

            if (isset($user['profile']['gender'])) {
                $profileEntity
                    ->setGender($user['profile']['gender'])
                ;
            }

            if (isset($user['profile']['birthdate'])) {
                $profileEntity
                    ->setBirthdate($user['profile']['birthdate'])
                ;
            }

            // User
            $userEntity
                ->setId($user['id'])
                ->setUsername($user['username'])
                ->setEmail($user['email'])
                ->setPlainPassword(
                    $user['plainPassword'],
                    $app['security.encoder_factory']
                )
                ->setRoles($user['roles'])
                ->setProfile($profileEntity)
                ->enable()
            ;

            $app['orm.em']->persist($userEntity);
        }

        // Save them, because we'll need them soon!
        $app['orm.em']->flush();

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
        $range = range(-64, 64);
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

        // Country
        $countryEntity = new CountryEntity();
        $countryEntity
            ->setId(1)
            ->setName('Panem')
            ->setSlug('panem')
            ->setDescription('The main country')
        ;
        $app['orm.em']->persist($countryEntity);

        // Town
        $townEntity = new TownEntity();
        $townEntity
            ->setId(1)
            ->setName('Panonia')
            ->setSlug('panonia')
            ->setDescription('The main town')
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

        try {
            $app['orm.em']->flush();

            $output->writeln('<info>Data was successfully hydrated!</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }
}
