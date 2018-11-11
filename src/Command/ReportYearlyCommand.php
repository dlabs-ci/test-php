<?php

namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReportYearlyCommand extends ContainerAwareCommand
{
    const ARG_YEAR = 'year';

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Monthly breakdown of total views per profile for a given year')
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
        $year = $input->getArgument(self::ARG_YEAR);

        // set default value for the optional argument
        if (is_null($year)) {
            $year = date('Y');
        }

        /** @var array */
        $viewsData = $this->getContainer()->get('app.data_provider.views')->getSumViewsPerProfile($year);

        $mappedData = [ ];

        // map dataset in a single pass
        foreach ($viewsData as $dataRow) {
            $profileName = $dataRow['profile_name'];
            $monthNum = $dataRow['month_num'];

            $mappedData[$profileName][0] = $profileName;
            $mappedData[$profileName][$monthNum] = number_format($dataRow['sum_views'], 0);
        }

        // output styling
        $profileNames = array_keys($mappedData);
        $tableColumnWidths = array_fill(0, 13, 6);
        $longestProfileName = count($profileNames)
            ? max(array_map('strlen', $profileNames))
            : 0
        ;
        $headerYear = str_pad($year, max(9, $longestProfileName - strlen('Profile')), ' ', STR_PAD_LEFT);
        $headerCol_1 = 'Profile' . $headerYear;

        $tableHeader = [
            $headerCol_1, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ];

        $table = new Table($output);
        $table
            ->setColumnWidths($tableColumnWidths)
            ->setHeaders($tableHeader)
            ->setRows($mappedData)
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
