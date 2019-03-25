<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{

    private static $outputHeaderMonths = [1,2,3,4,5,6,7,8,9,10,11,12];

    private function getOutputHeaderMonths()
    {
        return self::$outputHeaderMonths;
    }

    private function getViewsDataNotAvailiableString()
    {
        return "n/a";
    }

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::OPTIONAL, 'Please input the year you wish the report to be generated for.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');
        
        $inputYear = $input->getArgument('year');
        if (intval($inputYear) === 0) {
            $displayDataValidYears = $db->query('SELECT YEAR(v.date) AS date_year FROM profiles p JOIN views v ON v.profile_id = p.profile_id WHERE v.views > "0" AND v.views IS NOT NULL GROUP BY YEAR(v.date) ORDER BY YEAR(v.date) DESC')->fetchAll();
            
            $yearChoices = [];
            foreach ($displayDataValidYears as $displayDataValidYear) {
                $yearChoices[] = $displayDataValidYear['date_year'];
            }

            if (count($yearChoices)) {
                $inputYear = $io->choice('For which year would you like to display the report?', $yearChoices, $yearChoices[0]);
            } else {
                $io->caution("Error! There is no data availiable!");
            }
            
        }

        $profiles = $db->query('SELECT p.profile_name, MONTH(v.date) as date_month, SUM(v.views) AS sum_views FROM profiles p JOIN views v ON v.profile_id = p.profile_id WHERE v.date >= "'.$inputYear.'-01-01" AND v.date <= "'.$inputYear.'-12-31" GROUP BY p.profile_name, MONTH(v.date) ORDER BY p.profile_name ASC')->fetchAll();

        $outputHeader = ['Profile          '.$inputYear];
        foreach ($this->getOutputHeaderMonths() as $outputHeaderMonth) {
            $outputHeader[] = date('M', strtotime("2019-".str_pad($outputHeaderMonth, 2, "0", STR_PAD_LEFT)."-01"));
        }

        $outputData = [];
        foreach ($profiles as $profile) {
            $this->newOutputDataProfileEntry($outputData, $profile['profile_name']);

            if (!isset($outputData[$profile['profile_name']][$profile['date_month']])) {
                $outputData[$profile['profile_name']][$profile['date_month']] = $profile['sum_views'];
            } else {
                $outputData[$profile['profile_name']][$profile['date_month']] += $profile['sum_views'];
            }
            
        }

        if (count($outputData) === 0) {
            $io->caution("No data exists for the chosen year!");
        } else {
            $this->checkOutputData($outputData);

            $io->table($outputHeader, $outputData);
        }

        
    }

    private function newOutputDataProfileEntry(&$outputData, $profileName)
    {
        if (!is_array($outputData) || trim($profileName) === "") {
            return;
        }

        if (!isset($outputData[$profileName])) {
            $outputData[$profileName] = [
                'profile_name' => $profileName,
            ];

            foreach ($this->getOutputHeaderMonths() as $outputHeaderMonth) {
                $outputData[$profileName][$outputHeaderMonth] = 0;
            }
        }
    }

    private function checkOutputData(&$outputData)
    {
        if (is_array($outputData) && count($outputData)) {
            foreach ($outputData as $keyProfileName => $valData) {
                foreach ($this->getOutputHeaderMonths() as $outputHeaderMonth) {
                    if (!isset($valData[$outputHeaderMonth]) || $valData[$outputHeaderMonth] === 0) {
                        $outputData[$keyProfileName][$outputHeaderMonth] = $this->getViewsDataNotAvailiableString();
                    }
                }
            }
        }
    }
}
