<?php

namespace BOF\Command;

use BOF\Repository\ProfileRepository;
use BOF\Repository\ProfileViewRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use BOF\Service\ReportHelper;
use BOF\Service\Report;

class ReportCommand extends Command
{
    protected static $defaultName = 'report:profiles:yearly';
    protected $profileRepository;
    protected $profileViewRepository;

    public function __construct(ProfileViewRepository $profileViewRepository, ProfileRepository $profileRepository)
    {
        $this->profileRepository    = $profileRepository;
        $this->profileViewRepository= $profileViewRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('generate report.')
            ->addArgument('year', InputArgument::REQUIRED, 'Set valid year')
            ->setHelp('This command allows you to generate a report...')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $report = new Report($this->profileViewRepository, $this->profileRepository, Report::REPORT_TYPE_CONSOLE);
        $year = $input->getArgument('year');
        $reportData = $report->generateReport([ProfileViewRepository::QUERY_YEARS => $year]);
        $io = new SymfonyStyle($input, $output);

        if ( $reportData['status'] == 'success' ){
            $header = ReportHelper::$months;
            $header[0] = 'Profiles ' . $year;
        } else {
            $header = ['Errors'];
            // wrapper for io table
            $reportData['data'] = [$reportData['data']];
        }
        $io->table($header, $reportData['data']);
    }
}