<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use PDO;
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
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        $profiles = $db->query('SELECT profile_id, profile_name FROM profiles ORDER BY profile_name')->fetchAll();
        $years = $db->query('SELECT DISTINCT YEAR(date) as YEAR FROM views')->fetchAll(PDO::FETCH_COLUMN, 0);
        $months = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );

        foreach ($years as $year){
            $data = [];
            foreach ($profiles as $profile){
                $data[$profile['profile_id']][] = $profile['profile_name'];
                foreach ($months as $index=>$month){
                    $sum_query = $db->prepare('SELECT SUM(views), IFNULL(SUM(views), \'n/a\') FROM views WHERE profile_id =:profile_id AND YEAR(date)=:bind_year AND MONTH(date)=:bind_month');
                    $sum_query->execute(['bind_year' => $year, 'profile_id' => $profile['profile_id'], 'bind_month' => $index+1]);
                    $sum = $sum_query->fetchAll(PDO::FETCH_COLUMN, 1);
                    $data[$profile['profile_id']][] = is_numeric($sum[0]) ? number_format($sum[0]) : $sum[0];
                }
            }
            // Show data in a table - headers, data
            $headers = array_merge(['Profile - ' . $year], $months);
            $io->table($headers, $data);
        }
    }
}
