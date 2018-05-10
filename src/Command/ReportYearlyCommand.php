<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use \Datetime;


class ReportYearlyCommand extends ContainerAwareCommand
{
    /**
     * @param int $year     The year of the report
     * @return              Display table of month views by month in a Year
     */
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('Year', InputArgument::REQUIRED, 'Year to display');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        /** @var $year Selected year to display DATA */
        $year = $input->getArgument('Year');

        /** Check if $year exist and check input format */
        if(isset($year) && is_numeric($year) && strlen($year) == 4) {

            /** @sql_query Start Build main SQL query to display data */
            $sql_query = 'SELECT profiles.profile_name, ';

            /** Start building Table header */
            $table_header = array('Profile '.$year);

            for ($m=1; $m<=12; $m++) {

                /** Get Month name and month ID */
                $dateObj   = DateTime::createFromFormat('!m', $m);
                $month_name = $dateObj->format('M');
                $month_id = $dateObj->format('m');

                /** Build Table header */
                array_push($table_header, $month_name);

                /** @sql_query Building main data query */
                $sql_query .= 'sum(case when MONTH(views.date)='.$month_id.' then views.views end) "'.$month_name.'", ';
            }

            /** @sql_query Building main data query */
            $sql_query = substr($sql_query, 0, -2);
            $sql_query .= ' FROM `views` JOIN profiles ON profiles.profile_id = views.profile_id WHERE YEAR(views.date) = ' . $year . ' GROUP BY views.profile_id';

            $profiles = $db->query($sql_query)->fetchAll();
            
            /** Empty values to strin 'N/A' */
            $profiles = ArrayReplace::arrayReplace($profiles, 'N/A');


            // Show data in a table - headers, data
            $io->table($table_header, $profiles);


        }else{
            $output->writeln('The YEAR variable is not year format : YYYY ');
        }

    }
}
