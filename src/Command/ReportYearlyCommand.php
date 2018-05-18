<?php

namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
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
            ->addArgument('year', InputArgument::REQUIRED, 'Which year should I show?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);

        $year = $input->getArgument('year');

        if (!ctype_digit($year)) {
            $io->write('Entered value is not a valid year. Only numbers are valid input.');
            return;
        }

        if ($year < 2000 || $year > date("Y")) {
            $io->write('Year must me between 2000 and ' . date("Y"));
            return;
        }

        $db = $this->getContainer()->get('database_connection');

        /*
         * Aggregate data summed for month and profile_id for selected year
         */

        $aggregatedData = $db->query('SELECT views.profile_id, MONTH(views.date) as month, SUM(views.views) as total_views FROM views WHERE YEAR(date) = ' . $year . ' GROUP BY profile_id, MONTH(date)')->fetchAll();

        /*
         * If there's no data for given year, tell that to user and stop.
         */
        if (count($aggregatedData) === 0) {
            $io->write('No data found for selected year!');
            return;
        }

        $profiles = $db->query('SELECT profile_name, profile_id FROM profiles')->fetchAll();

        /*
         * Iterate thru aggregated data and make sure that all profile ids exist in profiles table. If not, add it to profile array.
         */
        foreach ($aggregatedData as $item) {
            if (!$this->search_array_by_values(['profile_id' => $item['profile_id']], $profiles)) {
                $profiles[] = [
                    'profile_name' => 'N/A',
                    'profile_id' => $item['profile_id']
                ];
            }
        }

        /*
         * Iterate thru profiles and months and assign data for each month to each user. If no data found put in N/A
         */
        $mergedData = array();
        foreach ($profiles as $key => $profile) {

            $mergedData[$key]['profile_name'] = $profile['profile_name'];

            for ($i = 0; $i < 12; $i++) {
                $data = $this->search_array_by_values([
                    'profile_id' => $profile['profile_id'],
                    'month' => $i + 1
                ], $aggregatedData);
                if ($data) {
                    $mergedData[$key][$i] = $data['total_views'];
                } else {
                    $mergedData[$key][$i] = 'N/A';
                }
            }
        }

        /*
         * Sort data by profile_name
         */
        usort($mergedData, function ($a, $b) {
            return $a['profile_name'] <=> $b['profile_name'];
        });

        /*
         * Generate headers for table
         */
        $headers = array('Profile ' . $year);
        for ($i = 0; $i < 12; $i++) {
            $headers[] = date("M", mktime(0, 0, 0, $i + 1, 1, 2000));
        }

        $io->table($headers, $mergedData);
    }

    /*
     * Search for multiple values (array) in array. If all values from $needle array are found
     * in $haystack (multidimensional array) it will return that item. If not all values are found, it will return false
     */

    protected function search_array_by_values($needle, $haystack)
    {
        foreach ($haystack as $item) {
            $found = 0;
            foreach ($needle as $key => $value) {
                if ($item[$key] == $value) {
                    $found++;
                }
            }
            if ($found === count($needle)) {
                return $item;
            }
        }
        return false;
    }
}
