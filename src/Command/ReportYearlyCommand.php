<?php

namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
    const ARG_YEAR = 'year';

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Yearly report of page views by user and month')
            ->addArgument(self::ARG_YEAR, InputArgument::OPTIONAL,
                "The 4-digit year for which to generate the report.\n"
                . " If not provided, the report will be generated for the current year.")
        ;
    }

    /**
     * Validate input arguments
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument(self::ARG_YEAR);

        if (!is_null($year) && !$this->validateYear($year)) {
            throw new \InvalidArgumentException("The optional argument 'year' should be a 4-digit number.");
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        $year = $input->getArgument(self::ARG_YEAR);

        // set default value for the optional argument
        if (is_null($year)) {
            $year = date('Y');
        }

        // sql select statement
        $sql =
           'SELECT
                p.profile_id,
                p.profile_name,
                MONTH(v.`date`) AS month_num,
                SUM(v.views) AS sum_views
            FROM profiles p
            LEFT OUTER JOIN views v ON p.profile_id = v.profile_id
            WHERE YEAR(v.`date`) = :year
            GROUP BY p.profile_id, month_num
            ORDER BY p.profile_name, month_num'
        ;

        /** @var PDOStatement */
        $stmt = $db->prepare($sql);
        $stmt->bindParam('year', $year);
        $stmt->execute();

        /** @var array */
        $queryResponse = $stmt->fetchAll();

        $result = [ ];

        // map resultset in a single pass
        foreach ($queryResponse as $responseRow) {
            $profileName = $responseRow['profile_name'];
            $monthNum = $responseRow['month_num'];

            $result[$profileName][0] = $profileName;
            $result[$profileName][$monthNum] = $responseRow['sum_views'];
        }

        $tableHeader = [
            'Profile', // @TODO: display year
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ];

        $table = new Table($output);
        $table
            ->setHeaders($tableHeader)
            ->setRows($result)
            ->render()
        ;
    }

    /**
     * Returns true if the given $year is in valid year format
     *
     * @param string $year
     * @return bool
     */
    private function validateYear(string $year)
    {
        return is_numeric($year) && strlen($year) === 4;
    }
}
