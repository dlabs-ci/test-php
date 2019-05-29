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
            ->addOption('year', null, InputOption::VALUE_OPTIONAL, 'Select for which year it will be imported.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);


        $year = $input->getOption('year');

        //Call to function that gets headers for the table
        $header = $this->getHeader($year);

        //Call to function that gets data for the table
        $profiles = $this->getDataForTable($header['header'], $header['year']);

        // Show data in a table - headers, data
        $io->table($header['header'], $profiles);

    }

    //Function that generates header
    protected function getHeader($year = null)
    {
        $db = $this->getContainer()->get('database_connection');
        try {

            if (empty($year)) {
                $year = $db->query("SELECT YEAR(date) AS year FROM views ORDER BY YEAR(date) DESC LIMIT 1")->fetchAll();
                $year = $year[0]['year'];
            }

            $sql = "SELECT date FROM views
                    WHERE YEAR(date) = '$year'
                    GROUP BY MONTH(date)
                    ORDER BY MONTH(date) ASC;";

            $headers = $db->query($sql)->fetchAll();

            $header_arr = ['0' => 'Profile '.$year];

            //Format the headers in a form, that can be used by the table
            foreach ($headers as $header) {
                $date = $header['date'];
                $header_arr[] = date("F", strtotime($date));
            }
        } catch (\Exception $e) {
            die("Error has occured". $e->getMessage());
        }
        return ['header' => $header_arr, 'year' => $year];
    }


    //Function that generates table data depending on the header
    protected function getDataForTable($headers, $year)
    {
        $db = $this->getContainer()->get('database_connection');

        $profiles_arr = [];

        try {
            $profiles = $db->query("SELECT * FROM profiles")->fetchAll();

            //Format profile names so that they can be used
            foreach ($profiles as $profile) {
                $profiles_arr[$profile['profile_id']][] = $profile['profile_name'];
            }

            //foreach of the month headers get the views
            foreach ($headers as $header) {

                //skip the first value in the array
                if (strstr($header, 'Profile')) {
                    continue;
                }

                //Format date value into something that can be used in mysql
                $date = date('Y-m-d', strtotime($year.' '.$header));

                // for each profile fill it up with views for that month
                foreach ($profiles_arr as $key => $profile) {
                    $sql = "SELECT SUM(v.views) as views FROM views v
                            WHERE profile_id = '$key'
                            AND MONTH(v.date) = MONTH('$date')
                            AND YEAR(v.date) = YEAR('$date')";

                    $views = $db->query($sql)->fetchAll();

                    //If views for that profile for that month exist format the data, otherwise insert N/A string for that month
                    if (!empty($views[0]['views'])) {
                        $profiles_arr[$key][] = number_format($views[0]['views'], 0, 0, '.');
                    } else {
                        $profiles_arr[$key][] = 'N/A';
                    }
                }
            }
        } catch (\Exception $e) {
            die("Error has occured". $e->getMessage());
        }
        return $profiles_arr;
    }
}
