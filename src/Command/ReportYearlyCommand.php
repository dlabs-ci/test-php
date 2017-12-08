<?php
declare(strict_types=1);

namespace BOF\Command;

use BOF\Entity\YearlyReportCalculatedRow;
use BOF\Service\ReportBuilderService;
use BOF\Service\Printer\ReportPrinterCollectorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends Command
{
    /** @var  ReportBuilderService */
    protected $reportBuilder;
    /** @var  ReportPrinterCollectorService */
    protected $reportPrinterCollector;

    public function setReportBuilder(ReportBuilderService $reportBuilder): self
    {
        $this->reportBuilder = $reportBuilder;
        return $this;
    }

    public function setReportPrinterCollector(ReportPrinterCollectorService $reportPrinterCollector): self
    {
        $this->reportPrinterCollector = $reportPrinterCollector;
        return $this;
    }

    protected function configure(): void
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year the report will be generated for', date('Y'))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input,$output);
//TODO: implement different out printers
//        $printerType = $this->askForPrinterType($input, $output);
//        $io->writeln(sprintf('%s report printer will be used', $printerType));

        try {
            $year =  $input->getArgument('year');
            $report = $this->reportBuilder->build($year);
            /** @var $row YearlyReportCalculatedRow */
            $data = array_map(function($row) {
                return $row->toArray();
                }, $report->getDataRows()->toArray());
            $io->table($report->getHeaderRow(), $data);
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }

    private function askForPrinterType(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');
        $supportedPrinters = $this->reportPrinterCollector->getSupportedPrinters();
        $outputFormatQuestion = new ChoiceQuestion(
            sprintf('Select output format (%s):', implode('/', $supportedPrinters)),
            $supportedPrinters,
            0
        );

        $outputFormatQuestion->setErrorMessage('Unsupported output format: %s.');

        return $helper->ask($input, $output, $outputFormatQuestion);
    }
}
