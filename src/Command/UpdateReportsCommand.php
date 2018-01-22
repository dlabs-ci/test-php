<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateReportsCommand extends ContainerAwareCommand
{
    protected $db;

    protected function configure()
    {
        $this
            ->setName('report:profiles:update')
            ->setDescription('Update reports table with fresh data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->db = $this->getContainer()->get('database_connection');

        // yesterday date
        $yesterday =  strtotime("-1 days");

        $year = date( 'Y',$yesterday);
        $month = date( 'n',$yesterday);

        // Get all views data for yesterdays month
        $month_data = $this->db->query('SELECT profile_id, views, MONTH(date) as month FROM views WHERE YEAR(date) = '.$year.' AND MONTH(date) ='.$month)->fetchAll();

        $views = $this->sum_views($month_data);

        $this->update_insert_report( $views, $month, $year);
    }

    /*********************************************
    * Format and update (or insert) data into reports table
    **********************************************/
    protected function update_insert_report($data, $month, $year)
    {
        foreach ( $data as $profile_id => $views ) 
        {
            $row = array();

            // MariaDB doesnt like Dec colum name ...
            if ( $month == 12)
            {
                $row['Dcm'] = $views;
            }
            else
            {
                $row[ date('M', strtotime('2000-'.$month.'-01')) ] = $views;
            }

            //Check if row exists
            $reports = $this->db->query('SELECT * FROM reports WHERE year = '.$year.' AND profile_id ='.$profile_id)->fetchAll();

            if ( sizeof($reports) )
            {
                $this->db->update('reports', $row, array('profile_id' => $profile_id, 'year' => $year));
            }
            else
            {
                $row['profile_id'] = $profile_id;
                $row['year'] = $year;
                $this->db->insert('reports', $row);
            }
        }
    }

    /*********************************************
    * Calculate number of monthly views
    **********************************************/
    protected function sum_views($rowset)
    { 
        $data = array();

        foreach ($rowset as $key => $row) 
        {
            if( !isset( $data[ $row['profile_id'] ]) )
            {
                $data[ $row['profile_id'] ] = 0;
            }

            $data[ $row['profile_id'] ] += $row['views'];
        }

        return $data;
    }
}
