<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Config\Definition\Exception\Exception;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            // Year as an argument
            ->addArgument('year', InputArgument::REQUIRED, 'Enter the year: ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Getting year from argument
        $year = $input->getArgument('year');
        // Checking if its valid
        if(strlen($year) != 4) {
            $output->write("Invalid YEAR. Please enter valid one.\n");
            exit;
        }

        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        // For ease of editing i would split sql query in query builder or smth like that,
        // but I'm not that good with symfony and doctrine.
        //
        // One more thing, for use of GROUP BY you have to execute this query:
        // SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
        try {
            $data = ("SELECT profiles.profile_name,
                    SUM(views.views) sum,
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '1' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '2' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '3' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '4' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '5' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '6' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '7' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '8' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) =  '9' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) = '10' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) = '11' THEN (views.views) END), 'N'),
                    FORMAT(SUM(CASE WHEN MONTH(views.date) = '12' THEN (views.views) END), 'N')
                    FROM profiles
                        LEFT JOIN views ON profiles.profile_id = views.profile_id
                    WHERE year(views.date) = $year
                    GROUP BY views.profile_id, year(views.date)");
        } catch (\Exception $ex) {
            $output->write("Query faild: " . $ex->getMessage());
        };

        // Query for order, maybe later you would need to sort it in other way
        $order = 'ORDER BY profiles.profile_name ASC';
        $profiles = $db->query($data . $order)->fetchAll();

        try {
            // Setting up the headers and print data.
            $io->table(['Profile ' . $year, 'Total',
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Avg', 'Sep', 'Oct', 'Nov', 'Dec'], $profiles);
        } catch (\Exception $ex) {
            $output->write("Failed to output: " . $ex->getMessage());
        };
    }

}
