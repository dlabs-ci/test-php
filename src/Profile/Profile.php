<?php

namespace BOF\Profile;

class Profile
{
    private $id;
    private $name;
    private $months;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function addViewsToMonth($month, $views)
    {
        if (isset($this->months[$month])) {
            $this->months[$month] += $views;
        } else {
            $this->months[$month] = $views;
        }
    }

    public function sortMonths()
    {
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($this->months[$i]))
                $this->months[$i] = "N/A";
        }

        ksort($this->months);
    }

    public function getMonths()
    {
        return $this->months;
    }
}