<?php
namespace BOF\Reports;

class ReportyViewCountByProfile
{

    public function __construct()
    {
    }

    public function getByYear($arg_year)
    {
        $profiles = ("SELECT pr.profile_name, count(vi.views) sum ,
                        COUNT(CASE WHEN MONTH(vi.date) = '1' THEN  (vi.views) END) as Jan,
                        COUNT(CASE WHEN MONTH(vi.date) = '2' THEN  (vi.views) END) as 'Feb',
                        COUNT(CASE WHEN MONTH(vi.date) = '3' THEN  (vi.views) END) as 'Mar',
                        COUNT(CASE WHEN MONTH(vi.date) = '4' THEN  (vi.views) END) as 'Apr',
                        COUNT(CASE WHEN MONTH(vi.date) = '5' THEN  (vi.views) END) as 'May',
                        COUNT(CASE WHEN MONTH(vi.date) = '6' THEN  (vi.views) END) as 'Jun',
                        COUNT(CASE WHEN MONTH(vi.date) = '7' THEN  (vi.views) END) as 'Jul',
                        COUNT(CASE WHEN MONTH(vi.date) = '8' THEN  (vi.views) END) as 'Avg',
                        COUNT(CASE WHEN MONTH(vi.date) = '9' THEN  (vi.views) END) as 'Sep',
                        COUNT(CASE WHEN MONTH(vi.date) = '10' THEN  (vi.views) END) as 'Oct',
                        COUNT(CASE WHEN MONTH(vi.date) = '11' THEN  (vi.views) END) as 'Nov',
                        COUNT(CASE WHEN MONTH(vi.date) = '12' THEN  (vi.views) END) as 'Dec'
                    FROM profiles pr
                        LEFT JOIN views vi on pr.profile_id = vi.profile_id
                    WHERE year(vi.date) = $arg_year
                    GROUP BY vi.profile_id, year(vi.date)
                    ORDER BY pr.profile_name");

        return $profiles;
    }
}
