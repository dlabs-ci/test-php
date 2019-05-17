<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use BOF\Domain\YearlyReport;
use BOF\Domain\TableDisplay;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputOption::VALUE_REQUIRED, 'Please select a year you are interested in:')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);        

        $db = $this->getContainer()->get('database_connection');
        // get year from cli arguments
        $year = $input->getArgument('year');

        // create the report
        $report = new YearlyReport($db, $year);
        
        // get report data
        $data = $report->getData();
        
        // generate heders
        $headers = $this->generateHeaders($year);
       
        // create display table
        $tableDisplay = new TableDisplay($headers);
        
        // ouput to the console
        $tableDisplay->display($data, $io);
    }

    private function generateHeaders($year){
        // first column
        $headers = array('Profile '.$year);
        for ($m=1; $m<=12; $m++) {
            // get english month name
            $month = date('F', mktime(0,0,0, $m, 1, date('Y')));
            // add first 3 letters to headers
            $headers[$m] = substr($month, 0, 3);
        }
        return $headers;
    }
}
