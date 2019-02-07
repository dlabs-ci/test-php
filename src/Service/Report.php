<?php

namespace BOF\Service;

use BOF\Entity\ProfileView;
use BOF\Repository\ProfileViewRepository;
use BOF\Repository\ProfileRepository;

Class Report
{
    const QUERY_TYPE_YEAR = 1;
    const REPORT_TYPE_CONSOLE = 1;

    private $reportType;

    private $response = [
        'status' => 'success',
    ];

    public function __construct(ProfileViewRepository $profileViewRepository,
                                ProfileRepository $profileRepository, $reportType)
    {
        $this->profileRepository = $profileRepository;
        $this->profileViewRepository = $profileViewRepository;
        $this->reportType = $reportType;
    }

    public function generateReport($queryArguments)
    {
        // todo add separate class for such responses with fields: status, data, etc,
        if (!$this->profileViewRepository->validate($queryArguments)) {
            $this->response['status'] = 'error';
            $this->response['data'] = $this->profileViewRepository->getErrors();
        } else {
            $this->response['data'] = $this->getResults($queryArguments);
        }

        return $this->response;
    }

    private function getResults($queryArguments)
    {
        $rows = $this->profileViewRepository->search($queryArguments);

        return $this->mapRows($rows);
    }

    private function mapConsoleOutput($rows)
    {
        $queryRes = [];
        $users = [];
        /** @var ProfileView $viewDay */
        foreach ($rows as $viewDay) {
            if (!isset($queryRes[$viewDay->getProfileId()][$viewDay->getMonth()])) {
                $queryRes[$viewDay->getProfileId()][$viewDay->getMonth()] = 0;
                $users [] = $viewDay->getProfileId();
            }
            $queryRes[$viewDay->getProfileId()][$viewDay->getMonth()] += $viewDay->getViews();
        }
        $res = [];

        // todo get username via profileView repository's query by using join
        foreach (array_unique($users) as $user) {
            $profileName = $this->profileRepository->find($user)->getName();
            $userArr = [$profileName];
            foreach (ReportHelper::$months as $key => $month) {
                $userArr [] = isset($queryRes[$user][$key]) ? $queryRes[$user][$key] : 'N/A';
            }
            $res [] = $userArr;
        }
        ksort($res);

        return $res;
    }

    private function mapRows($rows)
    {
        if (self::REPORT_TYPE_CONSOLE == $this->reportType) {
            return $this->mapConsoleOutput($rows);
        }
        // todo place for other map options
    }
}