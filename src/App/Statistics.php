<?php

namespace BOF\App;

class Statistics
{
    protected $db = null;
    public $year = null;
    public $months = null;
    protected $persons = null;
    protected $personStats = null;
    protected $personCurrentYearStat = null;

    public function __construct($db)
    {
        /** @var $this ->db Connection */
        $this->db = $db;

        for ($month = 1; $month <= 12; $month++) {
            $this->months[$month] = date("M", mktime(0, 0, 0, $month, 10));
        }
    }

    public function getYearStat($year = null, $showPersonName = false)
    {
        if (!$year) {
            die("Missing argument");
        }

        $personsClass = new Person($this->db);
        /* if personId(s) passed, get only mentioned perons */
        $this->persons = $personsClass->getPersons();

        $viewClass = new Views($this->db);

        foreach ($this->persons as $profileId => $profileName) {
            if (isset($this->personStats[$year][$profileId])) {
                continue;
            }

            $yearStatsTmp = $viewClass->getViewsByYear($profileId, $year);
            /* there must be some method to get sql "fetchPairs" to get array with own id as array key */
            $yearStats = array();
            foreach ($yearStatsTmp as $yearStat) {
                $yearStats[$yearStat['month']] = $yearStat['monthViews'];
            }
            /* end */

            $returnStats = array();
            if ($showPersonName) {
                $returnStats[0] = $profileName;
            }
            foreach ($this->months as $monthNumber => $month) {
                $returnStats[$monthNumber] = $this->formatNumber($yearStats[$monthNumber]);
            }

            $this->personStats[$year][$profileId] = $returnStats;
        }

        return $this->personStats[$year];
    }

    public function getMonths()
    {
        return $this->months;
    }

    public function formatNumber($number) {
        if (intval($number) == 0) {
            return 'n/a';
        }

        return number_format($number, 0, '.', ',');
    }
}