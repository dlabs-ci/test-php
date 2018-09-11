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
		
        $columns = array();
		$no_data = 'n/a';
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');
		
        $results = $db->query('SELECT * FROM profiles INNER JOIN views ON profiles.profile_id = views.profile_id ORDER BY date ASC, profile_name ASC')->fetchAll();
		
		if(!$results || count($results) == 0) {
			$io->text($no_data);
			return false;
		}
			
		//build array
        foreach($results as $row){
			
			$date = explode('-',$row['date']);
			$year = $date[0];
			$month = $date[1];
			$name = $row['profile_name'];
			
			//if current row hasn't been processed yet (doesn't exist in database) create a new empty array
			if(!isset($columns[$year][$name])){
				$columns[$year][$name] = array(
					'name'=>$name,'01'=>0,'02'=>0,'03'=>0,'04'=>0,'05'=>0,'06'=>0,'07'=>0,'08'=>0,'09'=>0,'10'=>0,'11'=>0,'12'=>0
					);
			}
			
			$views = (int) $columns[$year][$name][$month] + (int) $row['views'];
			$columns[$year][$name][$month] = $views;
			
        }
		
		//fix number format and add 'n/a' to empty results
		foreach($columns as $year=>$array1){
			foreach($array1 as $name=>$array2){
				foreach($array2 as $month=>$views){
					if(is_numeric($views) && $views > 0){
						$columns[$year][$name][$month] = number_format($views);
					}
					else if($month !== 'name') $columns[$year][$name][$month] = $no_data;
				}
			}
		}
		
        //show a table for each year
		foreach($columns as $year=>$data){
			$headers = array('Profile '.$year,'Jan','Feb','Mar','May','Apr','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

			$io->table($headers, $data);
		}

    }
}
