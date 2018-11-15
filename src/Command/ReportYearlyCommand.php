<?php

namespace BOF\Command;

use BOF\Repository\ProfilesRepository;
use InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
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
        $io = new SymfonyStyle($input, $output);

        $year = $input->getArgument('year');
        if (!filter_var($year, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1900
            ]
        ])) {
            $io->error('Year should be numeric and greater than ' . self::MIN_YEAR);
            return;
        }

        /** @var ProfilesRepository $profilesRepository */
        $profilesRepository = $this->getContainer()->get('repository.profiles');
        $profiles = $profilesRepository->getMonthlyViewsCount($year);

        if (count($profiles) == 0) {
            $io->error('No historical data');
            return;
        }

        // Pivot DB data
        $profilesPivot = $this->pivotData($profiles);

        // Fill non-existent values
        $this->fillEmpty($profilesPivot);

        // Sort profiles by name
        usort($profilesPivot, function ($a, $b)
        {
            return strcmp($a[0], $b[0]);
        });

        // using Table with TableStyle to make values right aligned
        $rightAligned = new TableStyle();
        $rightAligned->setPadType(STR_PAD_LEFT);

        $table = new Table($output);
        for ($i = 1; $i < count(self::MONTH_NAMES) + 1; $i++) {
            $table->setColumnStyle($i, $rightAligned);
        }

        // Display title
        $io->title('Yearly report for year ' . $year);

        $table->setHeaders(array_merge(['Profile'], self::MONTH_NAMES));
        $table->setRows($profilesPivot);
        $table->render();

    }

    /**
     * Pivots profiles data so that it can be passed to a Table component.
     *
     * @param $profiles array array of profiles
     * @return array DB data
     */
    private function pivotData(array $profiles): array
    {
        $profilesPivot = [];
        foreach ($profiles as $profile) {
            $profilesPivot[$profile['profile_id']][0] = $profile['profile_name'];
            $profilesPivot[$profile['profile_id']][$profile['month']] = number_format($profile['sum_views']);
        }
        return $profilesPivot;
    }

    /**
     * Fills empty values with 'n/a' when there is no monthly data available
     *
     * @param array $data array which will be checked for empty values
     */
    private function fillEmpty(array &$data)
    {
        foreach ($data as &$d) {
            for ($i = 1; $i < count(self::MONTH_NAMES) + 1; $i++) {
                if (!isset($d[$i])) {
                    $d[$i] = self::NOT_AVAILABLE;
                }
            }
            // Make sure that array elements are at correct places
            // (actually, converts a map to an array and preserves correct array positions)
            ksort($d);
        }
    }
}
