<?php

namespace BOF\Command;

use BOF\DataFixtures\AppFixtures;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BOF\Repository\ProfileRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResetCommand extends Command
{
    protected $profileRepository;
    protected $objectManager;
    protected static $defaultName = 'test:data:reset';

    public function __construct(ProfileRepository $profileRepository, ObjectManager $objectManager)
    {
        $this->profileRepository = $profileRepository;
        $this->objectManager = $objectManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('test:data:reset')
            ->setDescription('Reset MySQL data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // todo implement truncate option, because now command only add new fixtures
        $fixtures = new AppFixtures();
        $fixtures->profiles = $this->profileRepository->findAll();
        $fixtures->io = new SymfonyStyle($input, $output);
        $fixtures->load($this->objectManager);
    }
}
