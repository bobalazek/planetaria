<?php

namespace Application\Command\Database;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Entity\UserEntity;
use Application\Entity\ProfileEntity;
use Application\Entity\SkillEntity;
use Application\Entity\ResourceEntity;
use Application\Entity\DistrictEntity;
use Application\Entity\DistrictResourceEntity;
use Application\Entity\BuildingEntity;
use Application\Entity\BuildingResourceEntity;
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

        /***** Skills *****/
        $skills = include APP_DIR.'/fixtures/skills.php';
        foreach ($skills as $skill) {
            $skillEntity = new SkillEntity();

            // Skill
            $skillEntity
                ->setId($skill['id'])
                ->setName($skill['name'])
                ->setSlug($skill['slug'])
            ;
            $app['orm.em']->persist($skillEntity);
        }

        /***** Resources *****/
        $resources = include APP_DIR.'/fixtures/resources.php';
        foreach ($resources as $resource) {
            $resourceEntity = new ResourceEntity();

            // Resource
            $resourceEntity
                ->setId($resource['id'])
                ->setName($resource['name'])
                ->setSlug($resource['slug'])
            ;
            $app['orm.em']->persist($resourceEntity);
        }

        // Flush them, so we can use them soon!
        $app['orm.em']->flush();

        /***** Districts *****/
        $districts = include APP_DIR.'/fixtures/districts.php';
        foreach ($districts as $district) {
            $districtEntity = new DistrictEntity();

            $districtResources = array();

            if (isset($district['districtResources'])) {
                foreach ($district['districtResources'] as $districtResource) {
                    $districtResourceEntity = new DistrictResourceEntity();

                    $resourceEntity = $app['orm.em']
                        ->getRepository('Application\Entity\ResourceEntity')
                        ->findOneBySlug($districtResource['resource'])
                    ;

                    $districtResourceEntity
                        ->setDistrict($districtEntity)
                        ->setResource($resourceEntity)
                        ->setAmount($districtResource['amount'])
                        ->setAmountLeft($districtResource['amount'])
                    ;

                    $districtResources[] = $districtResourceEntity;
                }
            }

            // District
            $districtEntity
                ->setId($district['id'])
                ->setName($district['name'])
                ->setSlug($district['slug'])
                ->setDescription(isset($district['description']) ? $district['description'] : null)
                ->setCoordinatesX($district['coordinatesX'])
                ->setCoordinatesY($district['coordinatesY'])
                ->setDistrictResources($districtResources)
            ;
            $app['orm.em']->persist($districtEntity);
        }

        /***** Buildings *****/
        $buildings = include APP_DIR.'/fixtures/buildings.php';
        foreach ($buildings as $building) {
            $buildingEntity = new BuildingEntity();

            $buildingResources = array();

            if (isset($building['buildingResources'])) {
                foreach ($building['buildingResources'] as $buildingResource) {
                    $buildingResourceEntity = new BuildingResourceEntity();

                    $resourceEntity = $app['orm.em']
                        ->getRepository('Application\Entity\ResourceEntity')
                        ->findOneBySlug($buildingResource['resource'])
                    ;

                    $buildingResourceEntity
                        ->setBuilding($buildingEntity)
                        ->setResource($resourceEntity)
                        ->setAmount($buildingResource['amount'])
                    ;

                    $buildingResources[] = $buildingResourceEntity;
                }
            }

            // Building
            $buildingEntity
                ->setId($building['id'])
                ->setName($building['name'])
                ->setSlug($building['slug'])
                ->setDescription(isset($building['description']) ? $building['description'] : null)
                ->setType($building['type'])
                ->setSize($building['size'])
                ->setHealthPoints($building['healthPoints'])
                ->setPopulationCapacity($building['populationCapacity'])
                ->setStorageCapacity($building['storageCapacity'])
                ->setBuildTime($building['buildTime'])
                ->setBuildingResources($buildingResources)
            ;
            $app['orm.em']->persist($buildingEntity);
        }

        try {
            $app['orm.em']->flush();

            $output->writeln('<info>Data was successfully hydrated!</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }
}
