<?php

namespace Application\Command\Database;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Entity\UserEntity;
use Application\Entity\ProfileEntity;
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

        // Create a new planet!
        $app['game.planets']->generateNew(
            'Earth', 
            'earth', 
            'The main planet.'
        );
        $output->writeln('<info>The new planet was successfully created</info>');
        
        $app['game.countries']->prepareNew(
            $app['orm.em']->getRepository('Application\Entity\UserEntity')->findOneByUsername('borut'),
            array(
                'name' => 'Bananistan',
                'slug' => 'bananistan',
                'description' => 'The country of bananas.'
            ),
            array(
                'name' => 'Bananaland',
                'slug' => 'bananaland',
                'description' => 'The town of bananas.'
            ),
            $app['orm.em']->find('Application\Entity\PlanetEntity', 1),
            array(5, 5) // Start coordinates
        );
        $output->writeln('<info>The new country + town was successfully created</info>');
        
        // Create a new country

        $output->writeln('<info>Data was successfully hydrated!</info>');
    }
}
