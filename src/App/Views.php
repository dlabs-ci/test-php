<?php

namespace BOF\App;

use Doctrine\DBAL\Driver\Connection;

class Views
{
    protected $tableName = 'views';
    protected $db = null;
    public $views = null;

    function __construct($db)
    {
        /** @var $this ->db Connection */
        $this->db = $db;
    }

    public function getViews($profileIds = null)
    {
        /* TODO: implement multiple profileid query */
        $this->views = $this->db->query("
            SELECT *
            FROM {$this->tableName}
        ")->fetchAll();

        return $this->views;
    }

    public function getViewsByYear($profileId, $year)
    {
        if (isset($this->views[$profileId][$year])) {
            return $this->views[$profileId][$year];
        }

        $this->views[$profileId][$year] = $this->db->query("
            SELECT MONTH(date) as month, SUM(views) as monthViews
            FROM {$this->tableName}
            WHERE profile_id = {$profileId}
            AND YEAR(date) = {$year}
            GROUP BY MONTH(date)
            ORDER BY MONTH(date)
        ")->fetchAll();

        return $this->views[$profileId][$year];
    }

}