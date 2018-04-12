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
    const MONTHS = ['Jan','Feb','Mar','Apr', 'May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];

    const ARGUMENT_YEAR = "year";

    protected $sql = '
SELECT 
  profile_name, 
  IFNULL(FORMAT(sum(Jan), 0), "n/a"), 
  IFNULL(FORMAT(sum(Feb), 0), "n/a"), 
  IFNULL(FORMAT(sum(Mar), 0), "n/a"),
  IFNULL(FORMAT(sum(Apr), 0), "n/a"),
  IFNULL(FORMAT(sum(May), 0), "n/a"),
  IFNULL(FORMAT(sum(Jun), 0), "n/a"),
  IFNULL(FORMAT(sum(Jul), 0), "n/a"),
  IFNULL(FORMAT(sum(Avg), 0), "n/a"),
  IFNULL(FORMAT(sum(Sep), 0), "n/a"),
  IFNULL(FORMAT(sum(Oct), 0), "n/a"),
  IFNULL(FORMAT(sum(Nov), 0), "n/a"),
  IFNULL(FORMAT(sum(Decc), 0), "n/a")
FROM
(SELECT
  profile_name, 
  CASE MONTH(date) WHEN  1 THEN SUM(v.views) END AS Jan,
  CASE MONTH(date) WHEN  2 THEN SUM(v.views) END AS Feb,
  CASE MONTH(date) WHEN  3 THEN SUM(v.views) END AS Mar,
  CASE MONTH(date) WHEN  4 THEN SUM(v.views) END AS Apr,
  CASE MONTH(date) WHEN  5 THEN SUM(v.views) END AS May,
  CASE MONTH(date) WHEN  6 THEN SUM(v.views) END AS Jun,
  CASE MONTH(date) WHEN  7 THEN SUM(v.views) END AS Jul,
  CASE MONTH(date) WHEN  8 THEN SUM(v.views) END AS Avg,
  CASE MONTH(date) WHEN  9 THEN SUM(v.views) END AS Sep,
  CASE MONTH(date) WHEN 10 THEN SUM(v.views) END AS Oct,
  CASE MONTH(date) WHEN 11 THEN SUM(v.views) END AS Nov,
  CASE MONTH(date) WHEN 12 THEN SUM(v.views) END AS Decc
FROM profiles p LEFT JOIN views v ON (p.profile_id = v.profile_id)
WHERE YEAR(v.date) = :year
GROUP BY p.profile_name, MONTH(date)
ORDER BY p.profile_name) t
GROUP BY t.profile_name
';

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument(
                self::ARGUMENT_YEAR,
                InputArgument::OPTIONAL,
                "Which year to report for?",
                date('Y')
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        $year = $input->getArgument(self::ARGUMENT_YEAR);

        if (!is_numeric($year)) {
            $io->error("Year should be a number.");
            return;
        }


        // prepare sql statement which for each profile selects clicks for each month.
        // if there are no clicks, it outputs "n/a"
        $stmt = $db->prepare($this->sql);

        $stmt->execute([':year' => $year]);

        $report = $stmt->fetchAll();

        if (count($report) == 0) {
            $io->warning("No clicks to report for a year {$year}.");
            return;
        }

        // Show data in a table - headers, data
        $io->table(array_merge(["Profile ({$year})"], self::MONTHS), $report);
    }
}
