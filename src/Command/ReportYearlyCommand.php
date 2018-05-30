<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument(
                'year',
                InputArgument::OPTIONAL,
                'Enter a year to report only selected year. Default is current year. (format: Y)'
            )
            ->addOption(
                'name',
                NULL,
                InputOption::VALUE_REQUIRED,
                'Name of user profile. Default is all profiles'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);

        // Check for name option
        $name = FALSE;
        if (!empty($input->getOption('name'))) {
            $name = $input->getOption('name');
        }   

        // Check for year argument
        if (!empty($input->getArgument('year'))) {
            $year = $input->getArgument('year');
            $now = new \DateTime('now');
            if ($year > $now->format('Y')) {
                throw new \Exception("Wrong year. The selected year is in future!", 1);                
            }
        } else {
            $now = new \DateTime('now');
            $year = $now->format('Y');
        }
        $headers = ['Profile '.$year];
        for ($i=1; $i < 13; $i++) {
            if ($i<10) {
                $monthNum = '0'.$i;
            } else {
                $monthNum = $i;
            }
            
            $month = new \DateTime('01-'.$monthNum.'-'.$year);
            array_push($headers, $month->format('M'));
        }

        $data = $this->getDataForSelectedYear($year, $name);
        $data = $this->orderDataByMonths($data, $year);
        
        // Show data in a table - headers, data
        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($data);
        ;
        $table->render();

    }

    /**
     * Order data by months
     *
     * @param Array $data
     * @param int $year
     * @return Array
     */
    private function orderDataByMonths($data, $year)
    {
        $orderedData = [];
        foreach ($data as $profileName => $allValues) {
            for ($i=1; $i < 13; $i++) { 
                if ($i<10) {
                    $monthNum = '0'.$i;
                } else {
                    $monthNum = $i;
                }
                $month = new \DateTime('01-'.$monthNum.'-'.$year);
                $month = $month->format('M');
                $monthSum = 0;
                foreach ($allValues as $key => $value) {
                    if ($value['date']->format('M') == $month) {
                        $monthSum += $value['views'];
                    }
                }
                if ($monthSum == 0) {
                    $monthSum = "n/a";
                }
                $orderedData[$profileName][$month] = $monthSum;
            }            
        }
        // Add name to array
        foreach ($orderedData as $name => $months) {
            array_unshift($orderedData[$name], $name);
        }

        return $orderedData;
    }

    /**
     * Get data for selected year
     *
     * @param int $year
     * @param string $name
     * @return Array
     */
    private function getDataForSelectedYear($year, $name)
    {
        $db = $this->getContainer()->get('database_connection');
        if ($name) {
            $profiles = $db->query('SELECT * FROM profiles WHERE profile_name="'.$name.'"')->fetchAll();
            if (empty($profiles)) {
                throw new \Exception("There is no profile with this name in database!", 1);
            }            
        } else {
            $profiles = $db->query('SELECT * FROM profiles ORDER BY profile_name ASC')->fetchAll();
        }
        
        // Get array of yearly data foreach profile ordered by date
        foreach ($profiles as $profile) {
            $profileViewData = $db->query('SELECT * FROM views WHERE profile_id='.$profile["profile_id"].' ORDER BY date ASC')->fetchAll();
            $views[$profile['profile_name']] = $this->orderDates($profileViewData, $year);
        }

        return $views;
    }

    /**
     * Order dates in array, exclude wrong year
     *
     * @param Array $data
     * @param int $year
     * @return Array
     */
    private function orderDates($data, $year)
    {
        foreach ($data as $num => $values) {
            foreach ($values as $key => $value) {
                if ($key == 'date') {
                    $date = new \DateTime($value);
                    $data[$num][$key] = $date;
                    if ($date->format('Y') != $year) {
                        unset($data[$num]);
                    }
                }
            }

        }

        return $data;
    }
}
