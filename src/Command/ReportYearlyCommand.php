<?php

namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use BOF\App\Person;
use BOF\App\Views;
use BOF\App\Statistics;


class ReportYearlyCommand extends ContainerAwareCommand
{
    protected $persons = null;

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::REQUIRED, "Show data for year...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);
        $db = $this->getContainer()->get('database_connection');

        /* $year argument is required */
        $year = intval($input->getArgument('year'));
        /* TODO: better input error handeling */
        if (strlen($year) != 4) {
            die("Error! Invalid year number");
        }

        /* didn't spent time to figure out how to get connection in own class by not extending container class, getting just a connection, symphony stuff */
        $displayClass = new Statistics($db);
        $yearlyStats = $displayClass->getYearStat($year, true);

        $tableHeads = array_merge(array(
            'Profile'
        ), $displayClass->getMonths()
        );

        // Show data in a table - headers, data
        $io->table($tableHeads, $yearlyStats);

    }
}
