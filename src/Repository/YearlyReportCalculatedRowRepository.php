<?php
declare(strict_types=1);

namespace BOF\Repository;

use BOF\Entity\YearlyReport;
use BOF\Entity\YearlyReportCalculatedRow;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class YearlyReportCalculatedRowRepository extends EntityRepository
{
    public function hydrateReportWithRows(YearlyReport $yearReport): self
    {
        $columnsWithAlias = array_map(function ($m) { return "`$m` as `yrr.$m`"; }, YearlyReportCalculatedRow::$months);

        $sql = 'SELECT p.profile_id as `p.profile_id`, p.profile_name as `p.profile_name`, p.profile_id as `yrr.id`,';
        $sql .= implode(', ', $columnsWithAlias);
        $sql .= ' FROM profiles p ';

        foreach (YearlyReportCalculatedRow::$months as $month) {
            $sql .= sprintf(' LEFT JOIN (SELECT SUM(views.views) as `%1$s`, views.profile_id FROM views WHERE DATE_FORMAT(views.date, "%3$s") = \'%2$s-%1$s\' GROUP BY views.profile_id) %1$s_views ON %1$s_views.profile_id = p.profile_id ', $month, $yearReport->getYear(), YearlyReportCalculatedRow::$yearMonthDateFormat);
        }
        $sql .= ' ORDER BY p.profile_name';

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('BOF\Entity\Profile', 'p', 'p');
        $rsm->addFieldResult('p', 'p.profile_id', 'id');
        $rsm->addFieldResult('p', 'p.profile_name', 'name');
        $rsm->addJoinedEntityResult('BOF\Entity\YearlyReportCalculatedRow' , 'yrr', 'p', 'calculatedRows');
        //fake id from profile to make this data mapped to entity
        $rsm->addFieldResult('yrr', 'yrr.id', 'id');
        foreach (YearlyReportCalculatedRow::$months as $month) {
            $rsm->addFieldResult('yrr', 'yrr.'.$month, $month);
        }

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $profilesWithRows = $query->getResult(AbstractQuery::HYDRATE_OBJECT);
        foreach ($profilesWithRows as $profileRows) {
            foreach ($profileRows['p']->getCalculatedRows() as $row) {
                $row = clone $row;
                $this->getEntityManager()->persist($row);
                $yearReport->addDataRow($row);
            }
        }

        $this->getEntityManager()->persist($yearReport);

        return $this;
    }
}
