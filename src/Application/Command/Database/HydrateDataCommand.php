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
        $range = range(-16, 16);
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
                $image = $images[$rand];
                
                $tileEntity
                    ->setType('terrain')
                    ->setImage($image)
                    ->setCoordinatesX($x)
                    ->setCoordinatesY($y)
                    ->setPlanet($planetEntity)
                ;
                
                $app['orm.em']->persist($tileEntity);
            }
        }

        try {
            $app['orm.em']->flush();

            $output->writeln('<info>Data was successfully hydrated!</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }
}
