<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use BOF\Profile\Report;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('year', 'y', InputOption::VALUE_OPTIONAL),
                ])
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        $report = new Report($db);

        if (!$report->setYear($input->getOption("year"))) {
            $io->writeln("Year must be a valid integer.");
            return false;
        }
       
        if (!$report->setProfileViews())
            $io->table("Profile", []);
       
        $io->table($report->getHeaders(), $report->getProfiles());
    }
}
