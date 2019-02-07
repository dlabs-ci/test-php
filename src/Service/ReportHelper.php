<?php

namespace BOF\Service;

use BOF\Entity\ProfileView;
use BOF\Repository\ProfileViewRepository;
use BOF\Repository\ProfileRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

Class ReportHelper
{
//    const QUERY_TYPE_YEAR = 1;
//    const REPORT_TYPE_CONSOLE = 1;
//
//    public $year;
//    public $month;
//
//    private $profileViews = [];
//    private $profiles = [];
//
//    private $resultsArray = [];
//
//    public function __construct(ProfileViewRepository $profileViewRepository, ProfileRepository $profileRepository)
//    {
//        $this->profileRepository    = $profileRepository;
//        $this->profileViewRepository= $profileViewRepository;
    //}

    public static $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 =>'October',
        11 =>'November',
        12 =>'December',
    ];

//    public function generateReport($queryArguments, $type, $consoleInput = null)
//    {
//
//        $this->resultsArray = $this->getResults($queryArguments);
//
//        if (self::REPORT_TYPE_CONSOLE == $type) {
//            $this->outputConsole($consoleInput, $this->resultsArray);
//            return;
//        }
//    }
//
//    private function getResults($queryArguments) {
//        $rows = $this->profileViewRepository->search($queryArguments);
//
//    }
//
//    private function outputConsole($input, $output)
//    {
//        $io = new SymfonyStyle($input,$output);
//        $io->table(['Profile'], $output);
//    }
//
//    public function mapReportViewsToArray($queryResult, $repository) {
//        $queryRes = [];
//        $users = [];
//        /** @var ProfileView $viewDay */
//        foreach ($queryResult as $viewDay) {
//            if (!isset($res[$viewDay->getProfileId()][$viewDay->getMonth()])) {
//                $queryRes[$viewDay->getProfileId()][$viewDay->getMonth()] = 0;
//                $users []= $viewDay->getProfileId();
//            }
//            $queryRes[$viewDay->getProfileId()][$viewDay->getMonth()] += $viewDay->getViews();
//        }
//        $res = [];
//
//        // todo get username via profileView repository's query by using join
//        foreach ($users as $user) {
//            $profileName = $repository->find($user)->getName();;
//            foreach (self::$months as $key => $month) {
//                $res[$profileName][$month] = isset($queryRes[$user][$key]) ? $queryRes[$user][$key] : 'N/A';
//            }
//        }
//        ksort($res);
//        return $res;
//    }

}