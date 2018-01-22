<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::REQUIRED, 'Report for year')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');

        if ( !is_numeric($year) )
        {
            $output->writeln('Wrong year format!');
            return 0;
        }

        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        $sql = 'SELECT p.profile_name, 
                    IFNULL(Jan, "n/a") AS Jan, 
                    IFNULL(Feb, "n/a") AS Feb, 
                    IFNULL(Mar, "n/a") AS Mar, 
                    IFNULL(Apr, "n/a") AS Apr,
                    IFNULL(May, "n/a") AS May, 
                    IFNULL(Jun, "n/a") AS Jun, 
                    IFNULL(Jul, "n/a") AS Jul, 
                    IFNULL(Aug, "n/a") AS Aug, 
                    IFNULL(Sep, "n/a") AS Sep, 
                    IFNULL(Oct, "n/a") AS Oct, 
                    IFNULL(Nov, "n/a") AS Nov, 
                    IFNULL(Dcm, "n/a") AS Dcm 
                    FROM profiles p
                LEFT JOIN ( SELECT * FROM reports WHERE year = '.$year.' ) r
                ON r.profile_id = p.profile_id
                ORDER BY profile_name ASC';

        $profiles = $db->query($sql)->fetchAll();

        // Show data in a table - headers, data
        $io->table(['Profile - '.$year , 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Avg', 'Sep', 'Oct', 'Nov', 'Dec'], $profiles);

    }
}
