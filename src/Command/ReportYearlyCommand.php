<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $db = $this->getContainer()->get('database_connection');

        $profiles = $db->query("SELECT profiles.profile_name, DATE_FORMAT(date, '%Y') as Year,
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '1' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '2' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '3' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '4' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '5' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '6' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '7' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '8' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '9' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '10' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '11' THEN (views.views) END), 'N'), 'n/a'),
                    IFNULL(FORMAT(SUM(CASE WHEN MONTH(views.date) =  '12' THEN (views.views) END), 'N'), 'n/a')
                    FROM profiles
                        LEFT JOIN views ON profiles.profile_id = views.profile_id                        
                    GROUP BY views.profile_id, year(views.date) ORDER BY profiles.profile_name ASC")->fetchAll();

        try {
            // Setting up the headers and print data.
            $io->table(['Profile ' , 'Year',
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Avg', 'Sep', 'Oct', 'Nov', 'Dec'], $profiles);
        } catch (\Exception $ex) {
            $output->write("Failed to output: " . $ex->getMessage());
        };
    }
}
