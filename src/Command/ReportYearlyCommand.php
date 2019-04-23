<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use BOF\Service\YearlyViewsDataLoader;
use BOF\Service\ConsoleViewsDataRenderer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputOption::VALUE_OPTIONAL, 'Year for which the data will be generated.')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        /**
         * Ideally the classes used below could be injected 
         * in a constructor or as a method argument.
         */
        $dataLoader = new YearlyViewsDataLoader($db);

        $profilesVIews = $dataLoader
                    ->setYear($input->getArgument('year')[0])
                    ->load();

        $renderer = new ConsoleViewsDataRenderer;
        $renderer->render($io, $profilesVIews);
    }
}
