<?php
declare(strict_types=1);

namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestDataResetCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('test:data:reset')
            ->setDescription('Reset MySQL data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generating data');
        /** @var $db Connection */
        $db = $this->getContainer()->get('orm.entity_manager')->getConnection();
        $startDate = strtotime('2014-09-01');
        $endDate = strtotime('2017-02-11');

        $dataPerDay = 3;

        $db->query('TRUNCATE views');

        $profiles = $db->query('SELECT * FROM profiles')->fetchAll();

        $progress = $io->createProgressBar(count($profiles));
        foreach ($profiles as $profile) {

            $profileId = $profile['profile_id'];
            $currentDate = $startDate;

            while ($currentDate <= $endDate) {

                for ($i = 0; $i <= $dataPerDay; $i++) {

                    $views = rand(100, 9999);
                    $date = date('Y-m-d', $currentDate);

                    $sql = sprintf(
                        "INSERT INTO views (`profile_id`, `date`, `views`) VALUES (%s, '%s', %s)",
                        $profileId,
                        $date,
                        $views
                    );
                    $db->query($sql);
                }

                $currentDate = mktime(0,0,0, (int) date('m', $currentDate), (int) date('d', $currentDate) + 1, (int) date('Y', $currentDate));
            }
            $progress->advance();
        }

    }
}
