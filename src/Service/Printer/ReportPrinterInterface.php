<?php
declare(strict_types=1);

namespace BOF\Service\Printer;

use BOF\Entity\ReportInterface;

interface ReportPrinterInterface
{
    public function printReport(ReportInterface $report);
    public static function getType(): string;
}
