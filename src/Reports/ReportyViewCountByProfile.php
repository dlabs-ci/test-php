<?php
namespace BOF\Reports;

class ReportyViewCountByProfile
{

    public function __construct()
    {
        return $this;
    }

    public function getByYear($arg_year)
    {
        $profiles = ("SELECT pr.profile_name, count(vi.views) sum ,
        count(case when month(vi.date) = '1' then (vi.views) end) as Jan,
        count(case when month(vi.date) = '2' then (vi.views) end) as 'Feb',
        count(case when month(vi.date) = '3' then (vi.views) end) as 'Mar',
        count(case when month(vi.date) = '4' then (vi.views) end) as 'Apr',
        count(case when month(vi.date) = '5' then (vi.views) end) as 'May',
        count(case when month(vi.date) = '6' then (vi.views) end) as 'Jun',
        count(case when month(vi.date) = '7' then (vi.views) end) as 'Jul',
        count(case when month(vi.date) = '8' then (vi.views) end) as 'Avg',
        count(case when month(vi.date) = '9' then (vi.views) end) as 'Sep',
        count(case when month(vi.date) = '10' then (vi.views) end) as 'Oct',
        count(case when month(vi.date) = '11' then (vi.views) end) as 'Nov',
        count(case when month(vi.date) = '12' then (vi.views) end) as 'Dec'
            from profiles pr
        left outer join views vi on pr.profile_id = vi.profile_id
        where year(vi.date) = $arg_year
        group by vi.profile_id, year(vi.date)
        order by pr.profile_name");

        return $profiles;
    }
}
