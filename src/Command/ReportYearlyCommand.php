<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

use BOF\Models\Profile;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
     
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);
        $db = $this->getContainer()->get('database_connection');
        $profile = new Profile($db);
        if ($input->hasArgument('year') && $input->getArgument('year') != NULL) {
            $year = $input->getArgument('year');
        } else {
            $year = '2008';
        }
        $profiles = $profile->firstQuery($year)->fetchAll();

        // Show data in a table - headers, data
        $io->table(['Profile', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], $profiles);
    }
}
