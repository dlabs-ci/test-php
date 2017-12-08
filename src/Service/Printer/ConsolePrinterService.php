<?php
declare(strict_types=1);

namespace BOF\Service\Printer;

use BOF\Entity\ReportInterface;

class ConsolePrinterService implements ReportPrinterInterface
{

    public function printReport(ReportInterface $report)
    {
        // TODO: Implement printReport() method.
    }

    public static function getType(): string
    {
        return 'console';
    }
}