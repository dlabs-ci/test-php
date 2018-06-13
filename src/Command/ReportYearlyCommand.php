<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;
use BOF\Entity\Profile;
use Doctrine\ORM\Configuration;
use BOF\Entity\View;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    const DEFAULT_YEAR = 2016;

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
        $io = new SymfonyStyle($input,$output);
        
        $em = $this->getContainer()->get('entity_manager');
        $year = $input->hasArgument('year') && is_numeric($input->getArgument('year')) ? $input->getArgument('year') : self::DEFAULT_YEAR;
        $repo = $em->getRepository(Profile::class);
        $profiles = $repo->findWithViewsForYear($year);

        // // Show data in a table - headers, data
        $headers = $repo->months;
        $headers[0] = 'Profile ' . $year;
        ksort($headers);
        $io->table($headers, $profiles);
    }
}
