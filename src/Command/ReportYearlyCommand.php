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
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');
        
        /*
        $profiles = $db->query('SELECT profile_name FROM profiles')->fetchAll();

        // Show data in a table - headers, data
        $io->table(['Profile'], $profiles);
        */

        $profiles = $db->query('
            SELECT 
                `data`.`profile_name`,
                IF ( SUM(Jan) != 0, SUM(Jan), "n/a") AS Jan,
                IF ( SUM(Feb) != 0, SUM(Feb), "n/a") AS Feb,
                IF ( SUM(Mar) != 0, SUM(Mar), "n/a") AS Mar,
                IF ( SUM(Apr) != 0, SUM(Apr), "n/a") AS Apr,
                IF ( SUM(Maj) != 0, SUM(Maj), "n/a") AS Maj,
                IF ( SUM(Jun) != 0, SUM(Jun), "n/a") AS Jun,
                IF ( SUM(Jul) != 0, SUM(Jul), "n/a") AS Jul,
                IF ( SUM(Aug) != 0, SUM(Aug), "n/a") AS Aug,
                IF ( SUM(Spe) != 0, SUM(Spe), "n/a") AS Spe,
                IF ( SUM(Oct) != 0, SUM(Oct), "n/a") AS Oct,
                IF ( SUM(Nov) != 0, SUM(Nov), "n/a") AS Nov,
                IF ( SUM(`Dec`) != 0, SUM(`Dec`), "n/a") AS `Dec`
            FROM(
                SELECT
                    `profiles`.`profile_name`,
                    `profiles`.`profile_id`,
                    IF ( MONTH(date) = 1, SUM(views), 0)  AS Jan,
                    IF ( MONTH(date) = 2, SUM(views), 0)  AS Feb,
                    IF ( MONTH(date) = 3, SUM(views), 0)  AS Mar,
                    IF ( MONTH(date) = 4, SUM(views), 0)  AS Apr,
                    IF ( MONTH(date) = 5, SUM(views), 0)  AS Maj,
                    IF ( MONTH(date) = 6, SUM(views), 0)  AS Jun,
                    IF ( MONTH(date) = 7, SUM(views), 0)  AS Jul,
                    IF ( MONTH(date) = 8, SUM(views), 0)  AS Aug,
                    IF ( MONTH(date) = 8, SUM(views), 0)  AS Spe,
                    IF ( MONTH(date) = 8, SUM(views), 0)  AS oct,
                    IF ( MONTH(date) = 8, SUM(views), 0)  AS Nov,
                    IF ( MONTH(date) = 8, SUM(views), 0)  AS `Dec`
                FROM `profiles`
                LEFT JOIN `views` ON `profiles`.`profile_id` = `views`.`profile_id`
                GROUP BY `profiles`.`profile_id`, MONTH(`views`.`date`)
                ORDER BY `profiles`.`profile_name`
            ) AS data
            GROUP BY `data`.`profile_name`, `data`.`profile_id`
        ')->fetchAll();

        $io->table(['Profile',"Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Spe","Oct","Nov","Dec"], $profiles);

    }
}
