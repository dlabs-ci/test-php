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
    const ARG_YEAR = 'year';

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
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

        $profiles = $db->query('SELECT profile_name FROM profiles')->fetchAll();

        // Show data in a table - headers, data
        $io->table(['Profile'], $profiles);

    }

    private function validateYear($year)
    {
        return is_numeric($year) && strlen($year) === 4;
    }
}
