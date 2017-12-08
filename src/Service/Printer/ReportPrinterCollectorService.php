<?php
declare(strict_types=1);

namespace BOF\Service\Printer;

use BOF\Exception\PrinterNotFoundException;

class ReportPrinterCollectorService
{
    const REPORT_PRINTER_SERVICE_TAG = 'report.printer';

    private $printers = [];

    public function addPrinter(ReportPrinterInterface $printer): self
    {
        $this->printers[$printer::getType()] = $printer;
        return $this;
    }

    public function getSupportedPrinters(): array
    {
        return array_keys($this->printers);
    }

    public function getPrinterByType(string $type): ReportPrinterInterface
    {
        if (!array_key_exists($type, $this->printers)) {
            throw new PrinterNotFoundException(sprintf('Printer of unknown type "%s" requested', $type));
        }

        return $this->printers[$type];
    }
}
