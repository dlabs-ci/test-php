<?php
declare(strict_types=1);

namespace BOF\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use BOF\Service\Printer\ReportPrinterCollectorService;

class ReportPrinterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $printersCollectorDefinition = $container->getDefinition('app.report.printer.collector');
        $printers = $container->findTaggedServiceIds(ReportPrinterCollectorService::REPORT_PRINTER_SERVICE_TAG);
        foreach (array_keys($printers) as $printer) {
            $printersCollectorDefinition->addMethodCall('addPrinter', [new Reference($printer)]);
        }
    }
}
