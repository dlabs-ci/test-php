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
            ->addArgument(self::ARG_YEAR, InputArgument::REQUIRED, 'What year?');
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
        $stmt = $db->prepare('
            SELECT profile_name, MONTH(v.date) AS month, SUM(v.views) AS views
            FROM profiles p
            LEFT JOIN views v ON p.profile_id = v.profile_id
            WHERE YEAR(v.date) = ? OR v.profile_id IS NULL
            GROUP BY p.profile_id, month
        ');
        $stmt->bindValue(1, $year);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $headers = ['Profile ' . $year, 'month', 'views'];

        $io->table($headers, $data);
    }
}
