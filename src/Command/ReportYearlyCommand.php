<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tightenco\Collect\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->addArgument('year', InputArgument::REQUIRED, 'For which year do you need a report?')
            ->setDescription('Page views report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        if($input->getArgument('year')){
            $yearOfDisplay = $input->getArgument('year');
        }

        $profiles = collect($db->query('SELECT profile_name, date, views FROM profiles as p LEFT JOIN views as v ON p.profile_id=v.profile_id WHERE date LIKE "'.$yearOfDisplay.'-%" ORDER BY p.profile_name, v.date')->fetchAll());

        // Group by profiles and order by alphabetic order
        $export = $profiles->groupBy('profile_name')->sortBy('profile_name');

        // SUM for each user theirs data
        $export = $export->transform(function($personData){

            return $personData->groupBy(function($month){

                    return date('M', strtotime($month['date']));

                })->transform(function($monthValue){

                    return number_format($monthValue->sum('views'));
                });
        });

        // Display no data found.
        if(sizeof($export->toArray()) == 0){
           return $io->table(
                ['Profiles - '.$yearOfDisplay],
                [
                    ['n/a']
                ]
            );
        }

        // Design array for Symfony console display
        // Header with name of profiles and months
        $header = $profiles->sortBy('date')->groupBy(function($val) {

            return date('M', strtotime($val['date']));

        })->keys()->toArray();
        array_unshift($header, 'Profiles - '.$yearOfDisplay);


        // Add profile name in the same row as totals
        $data = [];
        foreach($export->toArray() as $name => $monthData){
            $data[$name] = $monthData;
            array_unshift($data[$name], $name);
        }

        return $io->table(
            $header,
            $data
        );

    }
}
