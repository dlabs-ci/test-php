<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportsCommand extends ContainerAwareCommand
{
    protected $db;

    protected function configure()
    {
        $this
            ->setName('report:profiles:generate')
            ->setDescription('Populate reports table')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->db = $this->getContainer()->get('database_connection');

        $border_dates = $this->db->query('SELECT MIN(date), MAX(date) FROM views')->fetchAll();

        $start_year = date("Y", strtotime($border_dates[0]['MIN(date)']) ) ;
        $end_year = date("Y", strtotime($border_dates[0]['MAX(date)']) );

        // Delete all data from reports table
        $this->db->query('DELETE FROM reports');

        $year = $start_year;
        while ( $year < $end_year )
        {
            // Get all views data for single year
            $year_data = $this->db->query('SELECT profile_id, views, MONTH(date) as month FROM views WHERE YEAR(date) = '.$year)->fetchAll();

            // Calculate monthly views for this year for each profile
            $monthly_views = $this->sum_yearly_views($year_data);

            // Insert report into reports table
            $this->insert_report($monthly_views, $year);

            $year++;
        }
    }
    /*********************************************
    * Format and insert data into reports table
    **********************************************/
    protected function insert_report($data, $year)
    {
        foreach ( $data as $profile_id => $year_data ) 
        {
            $row = array();
            $row['profile_id'] = $profile_id;
            $row['year'] = $year;

            foreach ( $year_data as $month => $views ) 
            {
                // MariaDB doesnt like Dec colum name ...
                if ( $month == 12)
                {
                    $row['Dcm'] = $views;
                }
                else
                {
                    $row[ date('M', strtotime('2000-'.$month.'-01')) ] = $views;
                }
            }

            $this->db->insert('reports', $row);
        }
    }

    /*********************************************
    * Calculate number of monthly views
    * for each profile for single year
    **********************************************/
    protected function sum_yearly_views($rowset)
    { 
        $data = array();

        foreach ($rowset as $key => $row) 
        {
            if( !isset( $data[ $row['profile_id'] ][ $row['month'] ]) )
            {
                $data[ $row['profile_id'] ][ $row['month'] ] = 0;
            }

            $data[ $row['profile_id'] ][ $row['month'] ] += $row['views'];
        }

        return $data;
    }
}
