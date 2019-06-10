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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = '2014';
        $tableHeads = array(
            0   => "Profile"
        );
        for ($month = 1; $month <= 12 ;$month++) {
            $tableHeads[$month] = date("M", mktime(0, 0, 0, $month, 10));
        }

        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        $rawData = $db->query('
            SELECT profiles.profile_id, profiles.profile_name, month(views.date) as month, sum(views.views) as sum_views
            FROM views
            JOIN profiles ON profiles.profile_id = views.profile_id
            WHERE year(views.date) = 2015
            GROUP BY month(views.date), views.profile_id
            ORDER BY views.profile_id, month(views.date)
        ')->fetchAll();

        $tableData = array();
        foreach ($rawData as $row) {
            if (!isset($tableData[$row['profile_id']])) {
                $tableData[$row['profile_id']]['Profile'] = $row['profile_name'];
            }
            $tableData[$row['profile_id']][$row['month']] = number_format($row['sum_views'], 0, ".", ",");
        }

        // Show data in a table - headers, data
        $io->table($tableHeads, $tableData);

    }
}
