<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use BOF\Reports\ReportyViewCountByProfile;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::REQUIRED, 'Report year');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);
        $db = $this->getContainer()->get('database_connection');

        $year_argument = $input->getArgument('year');

        if(strlen($year_argument) < 4){
            $output->write('Invalid input argument.' . PHP_EOL);
            $output->write('N/A' . PHP_EOL);
            exit;
        }

        $output->write('Generating report for year : ' . $year_argument . PHP_EOL);

        $report = new ReportyViewCountByProfile();

        $profiles = $db->query($report->getByYear($year_argument))->fetchAll();
        // Show data in a table - headers, data
        $io->table(['Profile ' . $year_argument, 
            'Sum', 'Jan', 'Feb', 
            'Mar', 'Apr', 'May', 
            'Jun', 'Jul', 'Avg', 
            'Sep', 'Oct', 'Nov', 'Dec'], 
            $profiles);

    }
}
