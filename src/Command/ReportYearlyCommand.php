<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;
use BOF\Entity\Profile;
use Doctrine\ORM\Configuration;
use BOF\Entity\View;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        // $db = $this->getContainer()->get('database_connection');

        // $profiles = $db->query('SELECT profile_name FROM profiles')->fetchAll();

        
        $em = $this->getContainer()->get('entity_manager');
        // $profile = $em->find(Profile::class, 1);
        $profile = $em->getRepository(Profile::class);
        $profiles = $profile->findWithViewsForYear(2014);
        // var_dump($profiles);exit;

        // // Show data in a table - headers, data
        $io->table(['Profile'], $profiles);

        // $profiles = $profile->findBy(['profile_id' => 1]);
        // var_dump($profiles[0]->getViews()[6]->getViews());

        // $stmt = $em->getConnection()->prepare($sql);
        // $stmt->execute();
        // var_dump($stmt->fetchAll());
    }
}
