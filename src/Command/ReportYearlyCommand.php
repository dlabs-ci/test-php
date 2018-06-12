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
        // $io = new SymfonyStyle($input,$output);
        // $db = $this->getContainer()->get('database_connection');

        // $profiles = $db->query('SELECT profile_name FROM profiles')->fetchAll();

        // // Show data in a table - headers, data
        // $io->table(['Profile'], $profiles);

        $em = $this->getContainer()->get('entity_manager');
        // $profile = $em->find(Profile::class, 1);
        $profile = $em->getRepository(Profile::class);

        $profiles = $profile->findBy(['profile_id' => 1]);
        var_dump($profiles[0]->getViews()[6]->getViews());

        // $sql = "SELECT views.profile_id, profile_name, 
        // (CASE WHEN (sum(CASE WHEN MONTH(`views`.`date`)=1 THEN views ELSE 0 END )) = 0 THEN 'n/a' ELSE sum(CASE WHEN MONTH(`views`.`date`)=1 THEN views ELSE 0 END ) END) as 'JAN',  
        // (CASE WHEN (sum(CASE WHEN MONTH(`views`.`date`)=9 THEN views ELSE 0 END )) = 0 THEN 'n/a' ELSE sum(CASE WHEN MONTH(`views`.`date`)=9 THEN views ELSE 0 END ) END) as 'SEP',
        // (CASE WHEN (sum(CASE WHEN MONTH(`views`.`date`)=10 THEN views ELSE 0 END )) = 0 THEN 'n/a' ELSE sum(CASE WHEN MONTH(`views`.`date`)=10 THEN views ELSE 0 END ) END) as 'OCT'
        // FROM bof_test.views  LEFT JOIN  bof_test.profiles ON views.profile_id=profiles.profile_id
        // WHERE YEAR(date) = 2014
        // GROUP BY profile_id, profile_name
        // ORDER BY profile_name";

        // $stmt = $em->getConnection()->prepare($sql);
        // $stmt->execute();
        // var_dump($stmt->fetchAll());
    }
}
