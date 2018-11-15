<?php
/**
 * Created by PhpStorm.
 * User: matej
 * Date: 15/11/2018
 * Time: 11:53
 */

namespace BOF\Repository;


use Doctrine\DBAL\Driver\Connection;

class ProfilesRepository {

    /**
     * A database connection.e
     * @var Connection
     */
    private $db;

    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * Returns views by profile, year and month.
     *
     * @param int|null $year specified year to filter. If null, returns data for all years
     * @return array array of profile_id, profile_name, year, month, sum_views
     */
    public function getMonthlyViewsCount(?int $year = null) : array {

        $binds = [];
        $sql = 'SELECT 
                    p.profile_id,
                    p.profile_name, 
                    YEAR(v.date) AS year, 
                    MONTH(v.date) AS month, 
                    SUM(v.views) AS sum_views
              FROM 
                  profiles p
              LEFT JOIN 
                  views v ON v.profile_id = p.profile_id';

        if ($year != null) {
            $sql .= ' WHERE YEAR(v.date) = :year';
            $binds['year'] = $year;
        }

        $sql .= '
              GROUP BY 
                  p.profile_id, p.profile_name, year, month
              ORDER BY 
                  p.profile_name, year, month';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($binds);
        return $stmt->fetchAll();

    }
}