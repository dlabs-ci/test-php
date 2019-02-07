<?php

namespace BOF\Controller;

use BOF\Entity\Profile;
use BOF\Entity\ProfileView;
use BOF\Repository\ProfileViewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use BOF\Repository\ProfileRepository;
use BOF\Service\ReportHelper;
use Symfony\Component\HttpFoundation\Response;
use BOF\Service\Report;


class DefaultController extends AbstractController
{
    public $profileRepository;
    public $profileViewRepository;


    public function __construct(ProfileViewRepository $profileViewRepository,
                                ProfileRepository $profileRepository)
    {
        $this->profileRepository    = $profileRepository;
        $this->profileViewRepository= $profileViewRepository;
    }

    public function index()
    {
        $profiles = $this->profileRepository->find(1);
        $profileRepository = $this->getDoctrine()->getRepository(Profile::class);
        $views = $this->getDoctrine()->getRepository(ProfileView::class);
        $report = new Report($this->profileViewRepository, $this->profileRepository, Report::REPORT_TYPE_CONSOLE);
        $reportData = $report->generateReport([ProfileViewRepository::QUERY_YEARS => 2016]);
        var_dump($reportData);
        echo '<pre>';
        print_r($reportData);
        die();
        return new Response('stop!');
    }
}