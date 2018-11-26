<?php
namespace BOF\Command;

use BOF\Reports\ReportyViewCountByProfile;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        try {
            /** @var $db Connection */
            $io = new SymfonyStyle($input, $output);

            $db = $this->getContainer()->get('database_connection');
            $year_argument = $input->getArgument('year');

            $output->write('Generating report for year : ' . $year_argument . PHP_EOL);

            $report = new ReportyViewCountByProfile();

            try {
                $profiles = $db->query($report->getByYear($year_argument))->fetchAll();
            } catch (\Doctrine\DBAL\Exception\SyntaxErrorException $ex) {
                $output->write('EXCEPTION : Exception ocurred while executing the query : ' . PHP_EOL . 'QUERY : ' . $report->getByYear($year_argument));
                return 0;
            };

            //We fill a dummy row that represents no data row 
            if (sizeof($profiles) === 0) {
                $profiles = array_fill(0, 1, array_fill(0, 13, 'N/A'));
            }

            // Show data in a table - headers, data
            $io->table(['Profile ' . $year_argument,
                'Sum', 'Jan', 'Feb',
                'Mar', 'Apr', 'May',
                'Jun', 'Jul', 'Avg',
                'Sep', 'Oct', 'Nov', 'Dec'],
                $profiles);

        } catch (\Exception $ex) {
            $output->write("Something unexpected happened!! OH-My-OH-My. : " . $ex->getMessage());
        };
    }
}
