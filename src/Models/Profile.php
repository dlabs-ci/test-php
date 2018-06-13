<?php

namespace BOF\Models;

/**
 * Description of Profile
 *
 * @author Nikolina Jovicic
 */
class Profile {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Method for get all profiles
     * @return type
     */
    public function getProfiles() {

        $profiles = $this->db->query('SELECT profile_name FROM profiles');
        return $profiles;
    }

    /**
     * Search profile by name
     * @param type $name
     * @return type
     */
    public function findByName($name) {
        return $this->db->query("SELECT profile_name FROM profiles where profile_name='$name'");
    }

    /**
     * Method for filter historical data
     * @param type $year
     * @return type
     */
    public function historicalDataQuery($year) {

        return $this->db->query("SELECT  profile_name, 
if(sum(CASE WHEN MONTH(date)=1 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=1 THEN views ELSE 0 END )) as 'JAN',
if(sum(CASE WHEN MONTH(date)=2 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=2 THEN views ELSE 0 END )) as 'FEB',
if(sum(CASE WHEN MONTH(date)=3 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=3 THEN views ELSE 0 END )) as 'MAR',
if(sum(CASE WHEN MONTH(date)=4 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=4 THEN views ELSE 0 END )) as 'APR',
if(sum(CASE WHEN MONTH(date)=5 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=5 THEN views ELSE 0 END )) as 'MAY',
if(sum(CASE WHEN MONTH(date)=6 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=6 THEN views ELSE 0 END )) as 'JUN',
if(sum(CASE WHEN MONTH(date)=7 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=7 THEN views ELSE 0 END )) as 'JUL', 
if(sum(CASE WHEN MONTH(date)=8 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=8 THEN views ELSE 0 END )) as 'AUG',        
if(sum(CASE WHEN MONTH(date)=9 THEN views ELSE 0 END )=0,'n/a',  sum(CASE WHEN MONTH(date)=9 THEN views ELSE 0 END )) as 'SEP',  
if(sum(CASE WHEN MONTH(date)=10 THEN views ELSE 0 END )=0,'n/a', sum(CASE WHEN MONTH(date)=10 THEN views ELSE 0 END )) as 'OCT',
if(sum(CASE WHEN MONTH(date)=11 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=11 THEN views ELSE 0 END )) as 'NOV',
if(sum(CASE WHEN MONTH(date)=12 THEN views ELSE 0 END )=0, 'n/a', sum(CASE WHEN MONTH(date)=12 THEN views ELSE 0 END )) as 'DEC' 
FROM bof_test.views  LEFT JOIN  bof_test.profiles ON views.profile_id=profiles.profile_id
WHERE YEAR(date) = $year
GROUP BY bof_test.profiles.profile_id
ORDER BY profile_name");
    }

    private $profile_name;

}
