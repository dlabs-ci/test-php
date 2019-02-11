<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use DB;

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

        $profiles = $db->query('SELECT profile_name FROM profiles')->fetchAll();
        
        //make result for only one date in              
        $views = $db->query('SELECT views.date, views.views, profiles.profile_name FROM views INNER JOIN profiles ON profiles.profile_id=views.profile_id WHERE date = "2014/09/01" GROUP BY date, views, profile_name')->fetchAll();

        //make result for only one date and one user
        $oneUsers = $db->query('SELECT views.date, views.views, profiles.profile_name FROM views INNER JOIN profiles ON profiles.profile_id=views.profile_id WHERE date = "2014/09/01" AND profile_name = "Tom Ford" GROUP BY date, views, profile_name')->fetchAll();

        //make result for only one year and for one user
        $oneYear = $db->query('SELECT views.date, views.views, profiles.profile_name FROM views INNER JOIN profiles ON profiles.profile_id=views.profile_id WHERE DATE_FORMAT(date, "%Y") = "2014" AND profile_name = "Sandra Choi" GROUP BY date, views, profile_name')->fetchAll();

        //make result for only one month, for one user in each year
        $oneMonth = $db->query('SELECT views.date, views.views, profiles.profile_name FROM views INNER JOIN profiles ON profiles.profile_id=views.profile_id WHERE DATE_FORMAT(date, "%m") = "02" AND profile_name = "Sandra Choi" GROUP BY date, views, profile_name')->fetchAll();

        //make result between date for each profile_name              
        $oneMonthYear = $db->query('SELECT views.date, views.views, profiles.profile_name FROM views INNER JOIN profiles ON profiles.profile_id=views.profile_id WHERE date >= "2014/09/01" AND date < "2014/09/25" GROUP BY date, views, profile_name')->fetchAll();


        // Show data in a table - headers, data
        $io->table(['Date', 'Views', 'Profile_name'], $views);
        $io->table(['Date', 'Views', 'Profile_name'], $oneUsers);
        $io->table(['Date', 'Views', 'Profile_name'], $oneYear);
        $io->table(['Feb', 'Views', 'Profile_name'], $oneMonth);
        $io->table(['Date', 'Views', 'Profile_name'], $oneMonthYear);
        
    }
}
