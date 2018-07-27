<?php

namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch(true);
        $stopwatch->start('execution');

        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);
        $db = $this->getContainer()->get('database_connection');


        $tableHeaderTemplate = array_map(function ($monthNum) {
            return $monthNum == 0 ? str_pad("Profile", 20) : date('M', mktime(null, null, null, $monthNum));
        }, range(0, 12));

        $reportData = $this->collectData($db);

        $table = new Table($output);
        $table->setColumnWidths([24] + array_fill(0, 13, 9));
        $table->setColumnStyle(0, (new TableStyle())->setPadType(STR_PAD_RIGHT));
        $table->setStyle((new TableStyle())->setPadType(STR_PAD_LEFT));

        //Table::setStyleDefinition((new TableStyle())->setPadType(STR_PAD_LEFT));
        foreach ($reportData as $year => $viewData) {
            $header = $tableHeaderTemplate;
            $header[0] .= $year;

            $table->setRows([]); //remove all previous rows
            $table->setHeaders($header);
            $table->setRows($viewData);
            $table->render();
            //$io->table($header, $viewData);
        }

        $event = $stopwatch->stop('execution');
        $io->writeln(sprintf("execution time: %s ms", $event->getDuration()));
        $io->writeln(sprintf("consumed memory: %s MB", round(memory_get_peak_usage() / (1024 * 1024), 2)));

    }

    private function collectData($db)
    {
        $rowDataTemplate = array_fill(0, 13, 'n/a');

        $sql = "
        SELECT 
            YEAR(v.date) AS year,
            v.profile_id,
            p.profile_name,
            SUM(v.views) AS view_count,
            MONTH(v.date) AS month
        FROM
            profiles p
                INNER JOIN
            views v ON v.profile_id = p.profile_id
        GROUP BY year , month , v.profile_id , p.profile_name
        ORDER BY year , p.profile_name , month
        ";
        $stm = $db->query($sql);

        $reportData = [];
        while ($row = $stm->fetch(\PDO::FETCH_NUM)) {
            list($year, $profileId, $profileName, $viewCount, $month) = $row;

            // No year with that profile - so add it
            if (!isset($reportData[$year][$profileId])) {
                $reportData[$year][$profileId] = $rowDataTemplate;
                $reportData[$year][$profileId][0] = $profileName;
            }

            $reportData[$year][$profileId][$month] = number_format($viewCount);
        }

        return $reportData;
    }
}

