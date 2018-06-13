<?php

namespace BOF\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Select;
use Doctrine\ORM\Query\ResultSetMapping;

class ProfileRepository extends EntityRepository
{
    /**
     * Array of months
     *
     * @var array
     */
    public $months = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
    ];

    /**
     * Get sum of profile views by months for given year
     *
     * @param integer $year The year for querying the profile views
     * @return array
     */
    public function findWithViewsForYear($year)
    {
        $sql = $this->buildQueryForYearViews($year);
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute([
            'year' => $year
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Generate query string for executing
     *
     * @param integer $year
     * @return string
     */
    protected function buildQueryForYearViews($year)
    {
        $sql = "SELECT profile_name, ";
        $sql .= $this->generateSelectsForYearViews();
        
        $sql .= "FROM bof_test.views  LEFT JOIN  bof_test.profiles ON views.profile_id = profiles.profile_id
        WHERE YEAR(date) = :year
        GROUP BY views.profile_id
        ORDER BY profiles.profile_name";

        return $sql;
    }

    /**
     * Generate select clauses for the yearly profile views
     *
     * @return string
     */
    protected function generateSelectsForYearViews()
    {
        $sql = '';
        foreach($this->months as $index => $month) {
            //Build CASE select clause for getting the needed values per month
            $queryPart = "sum(CASE WHEN MONTH(`views`.`date`) = {$index} THEN views ELSE 0 END )";
            $sql .= "(CASE WHEN ({$queryPart}) = 0 THEN 'n/a' ELSE {$queryPart} END) as '{$month}' ";

            if($index != count($this->months)) { //If the element is last in array, don't add comma
                $sql .= ', ';
            }
        }

        return $sql;
    }
}