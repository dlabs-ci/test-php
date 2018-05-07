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
            ->addArgument('year', InputArgument::REQUIRED, 'For what year you want the report?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        // get year from input
        $year = $input->getArgument('year');

        // check if the user entered a 4-digit number for the year 
        if( !preg_match('#^\d{4}$#si', $year) ){
            $io->error("$year is not a vaild year.");
            return;
        }

        $year = intval($year);

        // Get min and max year from the database
        $minMax = $db->query('SELECT MIN(YEAR(date)) as min, MAX(YEAR(date)) as max FROM `views`')->fetchAll();
        
        if( count($minMax) > 0 ){
            //If the entered year is to low, inform the user and exit.
            if( intval($minMax[0]["min"]) > $year ){
                $io->note("There is no data before the year {$minMax[0]["min"]}.");
                return;
            }

            //If the entered year is to high, inform the user and exit.
            if( intval($minMax[0]["max"]) < $year ){
                $io->note("There is no data after the year {$minMax[0]["max"]}.");
                return;
            }
        }

        // Get the data for the provided year from the database. 
        // Data is grouped by profile id and by the month. 

        $sql = 'SELECT SUM(v.views) as month_views, MONTH(v.date) as month, YEAR(v.date) as year, p.profile_id, p.profile_name '.
               'FROM `views` v '.
               'INNER JOIN `profiles` p ON p.profile_id = v.profile_id '.
               'WHERE YEAR(date) = :year '.
               'GROUP BY p.profile_id, MONTH(v.date) '.
               'ORDER BY p.profile_name, month';

        $rawData = $db->executeQuery($sql, ["year" => $year])->fetchAll();

        $table = [];
        $profile = [];
        $lastProfile=-1;

        //Convert the data from 1 dimensional array to 2 dimensional array.
        
        if( count($rawData) > 0 ){

            // Data is sorted by profile. So all we need to do is sort it to the 2 dimensional array

            foreach($rawData as $i => $line){
                if( $lastProfile != $line["profile_id"] ){
                    // current line has the data for the next profile.
                    // We save the previous profile and initialize the new line.
                    // For the first record we don't have the data yet, so we skip this.
                    if( $i > 0) $table[] = $profile;

                    // Initialize the array for the profile. All profiles have the profile name as the first element and data for 12 months.
                    // Since we don't know how what monhs we have the data for, we just initialize it with 'n/a'.
                    $profile = [$line["profile_name"], 'n/a','n/a','n/a','n/a','n/a','n/a','n/a','n/a','n/a','n/a','n/a','n/a'];


                    $lastProfile = $line["profile_id"];
                }
                // save the current month views tto the correct position in the array.
                $profile[intval($line["month"])] = number_format(floatval($line["month_views"]));
            }
            
            // add the last profile
            $table[] = $profile;
            
        }

        // Show data in a table - headers, data
        $columnNames = ["Profile / ".$year,'Jan','Feb','Mar','Apr','May','Jun','Jul', 'Aug','Sep','Oct','Nov','Dec'];
        $io->table($columnNames, $table);

    }
}
