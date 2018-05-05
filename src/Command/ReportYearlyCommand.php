<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    const ARG_YEAR = 'year';

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument(self::ARG_YEAR, InputArgument::REQUIRED, 'Year for the report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $db = $this->getContainer()->get('database_connection');

         // Read arguments
        $year = $input->getArgument(self::ARG_YEAR);
        if (!is_numeric($year)) {
            $io->error('Invalid argument "' . self::ARG_YEAR . '": ' . $year);
            return;
        }

        // Fetch data
        $stmt = $db->prepare('CALL profile_views_yearly(?)');
        $stmt->bindValue(1, $year);
        $stmt->execute();
        $data = $stmt->fetchAll();

        // Create headers
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $headers = array_merge(['Profile ' . $year], $months);
        
        $io->table($headers, $data);
    }
}
