<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addArgument('year', InputArgument::REQUIRED, "Show data for year...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* $year argument is required */
        $year = $input->getArgument('year');
        /* TODO: check if valid year number */
        
        $months = array();
        for ($month = 1; $month <= 12 ;$month++) {
            $months[$month] = date("M", mktime(0, 0, 0, $month, 10));
        }
        $tableHeads = array_merge(array(
            0   => "Profile {$year}"
        ), $months);

        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        /* get profiles */
        $profiles = $db->query('
        SELECT profile_id, profile_name 
        FROM profiles
        ORDER BY profile_name
        ')->fetchAll();
        
        /* get view data grouped by monts and profile ids */
        /* TODO: possible sql injection, should escape with framework tools */
        $rawData = $db->query("
            SELECT profiles.profile_id, month(views.date) as month, sum(views.views) as sum_views
            FROM views
            JOIN profiles ON profiles.profile_id = views.profile_id
            WHERE year(views.date) = {$year}
            GROUP BY month(views.date), views.profile_id
            ORDER BY views.profile_id, month(views.date)
        ")
        ->fetchAll();

        /* sort data into multidimensional array by profile id and month */
        /* format number according the document */
        $viewsData = array();
        foreach ($rawData as $row) {
            $profileId = $row['profile_id'];
            $month = $row['month'];
            $views = $row['sum_views'];

            $viewsData[$profileId][$month] = number_format($views, 0, ".", ",");
        }
        
        /* build table data based on months array, profile array and view data array */
        $tableData = array();
        foreach ($profiles as $profile) {
            $profileId = $profile['profile_id'];
            $profileName = $profile['profile_name'];

            $tableData[$profileId]['profile'] = $profileName;
            foreach ($months as $monthNumber => $monthName) {
                $tableData[$profileId][$monthNumber] = ($viewsData[$profileId][$monthNumber]) ? 
                $viewsData[$profileId][$monthNumber]: 
                'n/a';
            }
        }

        // Show data in a table - headers, data
        $io->table($tableHeads, $tableData);

    }
}
