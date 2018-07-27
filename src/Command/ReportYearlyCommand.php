<?php

namespace BOF\Command;

use BOF\Command\Renderer\RendererFactory;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\Input;
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

        $reportData = $this->collectData($db);

        $renderer = RendererFactory::makeRenderer(RendererFactory::RENDERER_TABLE, $output);
        $renderer->render($reportData);

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

        $returnData = [];
        while ($row = $stm->fetch(\PDO::FETCH_NUM)) {
            list($year, $profileId, $profileName, $viewCount, $month) = $row;

            // No year with that profile - so add it
            if (!isset($returnData[$year][$profileId])) {
                $returnData[$year][$profileId] = $rowDataTemplate;
                $returnData[$year][$profileId][0] = $profileName;
            }

            $returnData[$year][$profileId][$month] = number_format($viewCount);
        }

        return $returnData;
    }
}
