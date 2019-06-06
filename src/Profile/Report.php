<?php

namespace BOF\Profile;

use BOF\Profile\Profile;
use Doctrine\DBAL\Connection;

class Report
{
    private $db;
    private $profiles = [];
    private $year;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function setProfileViews()
    {
        if (!isset($this->year))
            $this->setYear(date('Y'));

        return $this->getProfileViewsByYear();
    }

    public function getProfileViewsByYear()
    {
        $profiles = $this->db->query("SELECT p.profile_name, p.profile_id, date_format(v.date, '%m') dateMonth, v.views from profiles p left join views v on p.profile_id = v.profile_id AND YEAR(v.date) = $this->year")->fetchAll();

        if (!$profiles)
            return false;

        $this->sortProfileViews($profiles);

        return true;
    }

    public function getProfiles()
    {
        $return = [];

        foreach ($this->profiles as $profile) {
            $profile->sortMonths();
            $values = array_values($profile->getMonths());
            array_unshift($values, $profile->getName());
            $return[$profile->getName()] = $values;
        }

        ksort($return);
        return array_values($return);
    }

    public function getHeaders()
    {
        return ['Profile ' . $this->year, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    }

    private function sortProfileViews($profiles)
    {
        $sortedProfiles = [];

        foreach ($profiles as $view) {
            if (isset($sortedProfiles[$view["profile_id"]])) {
                $user = $sortedProfiles[$view["profile_id"]];
            } else {
                $user = new Profile($view["profile_id"]);
                $user->setName($view["profile_name"]);
                $sortedProfiles[$user->getId()] = $user;
            }

            if (isset($view["dateMonth"]))
                $user->addViewsToMonth((int) $view["dateMonth"], (int) $view["views"]);
        }

        $this->profiles = $sortedProfiles;
    }

    public function setYear($year)
    {
        if ((int) $year == 0)
            return false;

        $this->year = (int) $year;

        return true;
    }
}