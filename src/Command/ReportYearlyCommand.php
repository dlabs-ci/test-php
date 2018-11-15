<?php

namespace BOF\Command;

use BOF\Repository\ProfilesRepository;
use Doctrine\DBAL\Driver\Connection;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ReportYearlyCommand exposes command for yearly report of views per profile.
 *
 * @package BOF\Command
 */
class ReportYearlyCommand extends ContainerAwareCommand
{
    const MONTH_NAMES = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
    ];
    const NOT_AVAILABLE = 'n/a';
    const MIN_YEAR = 1900;

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::REQUIRED, 'Which year to display. ' .
                'Should be bigger than ' . self::MIN_YEAR);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);

        $year = $input->getArgument('year');
        if (!filter_var($year, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1900
            ]
        ])) {
            throw new InvalidArgumentException('Year should be numeric and greater than ' . self::MIN_YEAR);
        }

        /** @var ProfilesRepository $profilesRepository */
        $profilesRepository = $this->getContainer()->get('repository.profiles');
        $profiles = $profilesRepository->getMonthlyViewsCount($year);

        if (count($profiles) == 0) {
            $io->error('No historical data');
            return;
        }

        // pivot DB data
        $profilesPivot = $this->pivotData($profiles);

        // sort profiles by name
        usort($profilesPivot, function ($a, $b)
        {
            return strcmp($a[0], $b[0]);
        });

        $io->title('Yearly report for year ' . $year);

        // Show data in a table - headers, data
        $io->table(array_merge(['Profile'], self::MONTH_NAMES), $profilesPivot);

    }

    /**
     * Pivots profiles data so that it can be passed to a Table component.
     *
     * @param $profiles array array of profiles
     * @return array DB data
     */
    private function pivotData($profiles): array
    {
        $profilesPivot = [];
        foreach ($profiles as $profile) {
            $profilesPivot[$profile['profile_id']][0] = $profile['profile_name'];
            $profilesPivot[$profile['profile_id']][$profile['month']] = number_format($profile['sum_views']);
        }

        foreach ($profilesPivot as &$profilePivot) {
            for ($i = 1; $i < count(self::MONTH_NAMES) + 1; $i++) {
                if (!isset($profilePivot[$i])) {
                    $profilePivot[$i] = self::NOT_AVAILABLE;
                }
            }
            // make sure that array elements are at correct places
            // (actually, converts a map to array and preserves correct array positions)
            ksort($profilePivot);
        }
        return $profilesPivot;
    }
}
