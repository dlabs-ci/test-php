<?php

namespace BOF\App;

use Doctrine\DBAL\Driver\Connection;

class Person
{
    protected $tableName = 'profiles';
    protected $db = null;
    protected $persons = null;

    function __construct($db)
    {
        /** @var $this->db Connection */
        $this->db = $db;
    }

    public function getPersons()
    {
        if ($this->persons) {
            return $this->persons;
        }

        $personsTmp = $this->db->query("
            SELECT profile_id, profile_name 
            FROM {$this->tableName}
            ORDER BY profile_name
        ")->fetchAll();

        /* fetchPairs */
        foreach ($personsTmp as $person) {
            $this->persons[$person['profile_id']] = $person['profile_name'];
        }
        /* end */

        return $this->persons;
    }
}