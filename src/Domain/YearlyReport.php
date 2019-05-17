<?php
namespace BOF\Domain;

use Doctrine\DBAL\Connection;

class YearlyReport
{
  private $connection;
  private $sql;

  function __construct($conn, $year){
    if (is_null($conn)){
      throw new \RuntimeException('Supplied connection is null!');
    }
    if (is_null($year) || !is_numeric($year) || $year < 0){
      throw new \RuntimeException('Year should be an integer greater than 0!');
    }
    $this->connection = $conn;    
    $this->sql = $this->buildQuery($year);
  }

  public function getData(){
    return $this->connection->query($this->sql)->fetchAll();
  }

  public function buildQuery($year){
    $monthColumns = array();
    // create an array of sql sum statements so we can
    // pivot the table and diplay row contents as columns
    for($i = 0; $i < 12; $i++){
      $monthIndex = $i + 1;
      $monthColumns[$i] = 'SUM(CASE WHEN MONTH(v.date) = '.$monthIndex.' THEN v.views END) AS m'.$monthIndex;
    }
    // join into 1 string
    $monthColumns = implode(', ', $monthColumns);

    // build the query. sadly I do not know how to properly
    // bind parameters to the query in php, but checking it for
    // int should be enough to prevent sql injection
    $sql = 'SELECT p.profile_name as profileName, 
            '.$monthColumns.'
            FROM profiles p
            LEFT JOIN views v ON p.profile_id = v.profile_id
            WHERE YEAR(v.date) = '.$year.'
            GROUP BY p.profile_name
            ORDER BY profile_name;';   
    // echo($sql); 
    return $sql; 
  }
}