<?php
declare(strict_types=1);

namespace BOF\Manager;

use BOF\Entity\YearlyReport;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ReportManager
{
    /** @var  EntityRepository */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

//    public function save(YearlyReport $report): void
//    {
//        $this->em->flush($report);
//    }
}
