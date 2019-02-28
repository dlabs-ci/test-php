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

        $profiles = $db->query(
            'select profiles.profile_id, profiles.profile_name, year(date) as year, month(date) as month, sum(views) as views
                from profiles
                left join views v on
                    profiles.profile_id = v.profile_id
                group by profiles.profile_id, year(date), month(date)
                order by profiles.profile_name'
        )->fetchAll();

        // Show data in a table - headers, data
        $people = array();
        $years = array();

        foreach($profiles as $profile) {
            $id = $profile['profile_id'];
            $name = $profile['profile_name'];
            $year = $profile['year'];
            $month = $profile['month'];
            $views = $profile['views'];
            $person = null;

            foreach($people as $p) {
                if($id == $p->id) {
                    // Record found
                    $person = $p;
                    break;
                }
            }
            if($person == null) {
                $person = new Profile($id, $name);
                $people[] = $person;
            }

            if (!in_array($year, $years))
            {
                $years[] = $year;
            }

            $person->AddData($year, $month, $views);


        }
        $monthNames = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        foreach($years as $year) {
            $headers = array();
            $headers[] = 'Profile        '. $year;
            $headers = array_merge($headers, $monthNames);
            $dataAllPeopleWithinAYear = array();
            foreach($people as $p) {
                $data = array();

                if(array_key_exists($year, $p->years)) {
                    $data[] = $p->name;
                    foreach($p->years[$year]['months'] as $m) {
                        $data[] = $m['views'];
                    }
                }
                $dataAllPeopleWithinAYear[] = $data;
            }
            $io->table($headers, $dataAllPeopleWithinAYear);
        }

    }
}

class Profile
{
    public $id;
    public $name;
    public $years;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
        $this->years = array();
    }

    public function AddData($year, $month, $views) {
        if(!array_key_exists($year, $this->years)) {
            $this->years[$year] = array('months' => Profile::$monthsTemplate);
        }

        $tmp = $this->years[$year]['months'][$month]['views'];
        $this->years[$year]['months'][$month]['views'] = $tmp == 'n/a' ? $views : $views+$tmp;

    }

    public static $monthsTemplate = [
        1 => ['title' => 'Jan', 'views' => 'n/a'],
        2 => ['title' => 'Feb', 'views' => 'n/a'],
        3 => ['title' => 'Mar', 'views' => 'n/a'],
        4 => ['title' => 'Apr', 'views' => 'n/a'],
        5 => ['title' => 'May', 'views' => 'n/a'],
        6 => ['title' => 'Jun', 'views' => 'n/a'],
        7 => ['title' => 'Jul', 'views' => 'n/a'],
        8 => ['title' => 'Aug', 'views' => 'n/a'],
        9 => ['title' => 'Sep', 'views' => 'n/a'],
        10 => ['title' => 'Oct', 'views' => 'n/a'],
        11 => ['title' => 'Oct', 'views' => 'n/a'],
        12 => ['title' => 'Dec', 'views' => 'n/a'],
    ];

}
