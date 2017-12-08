<?php
declare(strict_types=1);

namespace BOF\Service;

use BOF\Entity\YearlyReport;
use BOF\Repository\YearlyReportCalculatedRowRepository;

class ReportBuilderService
{
    protected $reportRowRepository;

    public function __construct(YearlyReportCalculatedRowRepository $reportRowRepository)
    {
        $this->reportRowRepository = $reportRowRepository;
    }

    public function build(string $year, $save = false): YearlyReport
    {
        $yearlyReport = new YearlyReport($year);
        $this->reportRowRepository->hydrateReportWithRows($yearlyReport);
        return $yearlyReport;
// TODO: implement saving
//        if ($save) {
//
//        }
    }
}
