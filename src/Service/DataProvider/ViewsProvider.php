<?php

namespace BOF\Service\DataProvider;

use Doctrine\DBAL\Driver\Connection;

/**
 * Views data provider
 */
class ViewsProvider
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns an array of views per profile for a given year
     *
     * @param string|null $year
     * @return array
     */
    public function getSumViewsPerProfile($year = null)
    {
        // WHERE clause depends on optional argument $year
        $where = '1';

        if (!is_null($year)) {
            $where = 'YEAR(v.`date`) = :year';
        }

        // build SQL
        $sql =
           "SELECT
                p.profile_id,
                p.profile_name,
                MONTH(v.`date`) AS month_num,
                SUM(v.views) AS sum_views
            FROM profiles p
            LEFT OUTER JOIN views v ON p.profile_id = v.profile_id
            WHERE $where
            GROUP BY p.profile_id, month_num
            ORDER BY p.profile_name, month_num"
        ;

        $stmt = $this->connection->prepare($sql);

        // bind optional params
        if (!is_null($year)) {
            $stmt->bindParam('year', $year);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
