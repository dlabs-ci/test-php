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
    private static $db;
    private static $year;

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::REQUIRED, 'Year of review')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $this->setYear($input);
        
        $db = $this->getContainer()->get('database_connection');

        $profiles = $this->queryGetProfiles($db);
        if (empty($profiles)) {
            echo "There is no data available for that year. Please try another year";
            die;
       }

        $views_per_profile = $this->formateProfileData($profiles);
        $views_per_profile = ($this->setAllMonths($views_per_profile));
        $views_per_profile = $this->sortMonths($views_per_profile);

        $header = $this->getMonthsArray();
        array_unshift($header , "Profile     $this->year");

        $io->table($header, $views_per_profile);

    }
    private function queryGetProfiles($db){

        $sql = "SELECT  P.profile_id, P.profile_name,
		GROUP_CONCAT(CONCAT(MONTH(V.date), '/', V.views) SEPARATOR '-') as monthviews
		FROM views V
        JOIN profiles P ON (P.profile_id = V.profile_id)
        WHERE YEAR(V.date) = :year
        GROUP BY P.profile_id, P.profile_name;";

        $stmt = $db->prepare($sql);
        $stmt->execute(array(':year' => $this->year));

        return $stmt->fetchAll();
        

    } 
    private function formateProfileData($profiles){
        
        foreach ($profiles as $row){
            $profile_id = $row['profile_id'];
            $months = $points_arr = explode("-", $row['monthviews']);
            $views_per_profile[$profile_id]['_name'] = $row['profile_name'];
            foreach ($months as $views_per_month){
                $pos = strpos($views_per_month, '/');
		        $month = substr($views_per_month, 0, $pos);
                $views = substr($views_per_month, $pos+1, strlen($views_per_month)-1);
                if($month > 0){
                    if(isset($views_per_profile[$profile_id][$month])){
                        $views_per_profile[$profile_id][$month] = $views;
                    } else {
                        $views_per_profile[$profile_id][$month] += $views;
                    }
                }
            }
        }
    
        return ($views_per_profile);   
        
    }
    private function setAllMonths($views_per_profile){
        
        $months = $this->getMonthsArray();

        foreach ($months as $numMonth => $month){
            foreach ($views_per_profile as  $id_profile => $view){
                if(!(isset($views_per_profile[$id_profile][$numMonth]))){
                    $views_per_profile[$id_profile][$numMonth] = "0 / NA";
                }    
            }
        }
        return $views_per_profile;
    } 
    private function getMonthsArray(){
        $months = array(
            1 => "January",
            "February",
            "March", 
            "April", 
            "May",
            "June", 
            "July", 
            "August",
            "September",
            "October", 
            "November", 
            "December");

       return $months;
    }
    private function sortMonths($views_per_profile){
        foreach ($views_per_profile as $key => $profile){
            ksort($views_per_profile[$key]);
        }
        return $views_per_profile;
    }
    private function setYear($input){
        $this->year = $input->getArgument('year');
    }
}
