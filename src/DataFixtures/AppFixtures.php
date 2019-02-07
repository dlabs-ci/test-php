<?php

namespace BOF\DataFixtures;

use BOF\Entity\Profile;
use BOF\Entity\ProfileView;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public $profiles;
    public $io;

    public function load(ObjectManager $manager)
    {
        $dataPerDay = 3;
        $startDate = strtotime('2014-09-01');
        $endDate = strtotime('2017-02-11');

        $progress = $this->io->createProgressBar(count($this->profiles));
        /** @var Profile $profile */
        foreach ($this->profiles as $profile) {
            $profileId = $profile->getId();

            $currentDate = $startDate;
            while ($currentDate <= $endDate) {
                for ($i = 0; $i <= $dataPerDay; ++$i) {
                    $date = date('Y-m-d', $currentDate);
                    $model = new ProfileView();
                    $model->setProfileId($profileId);
                    $model->setData($date);
                    $model->setViews(rand(100, 9999));
                    $manager->persist($model);
                    $manager->flush();
                }
                $currentDate = mktime(0, 0, 0, date('m', $currentDate), date('d', $currentDate) + 1, date('Y', $currentDate));
            }
            $progress->advance();
        }
        $manager->flush();
    }
}
