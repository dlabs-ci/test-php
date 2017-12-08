<?php
declare(strict_types=1);

namespace BOF\Command;

use \Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class BaseCommand
 * @package BOF\Command
 */
abstract class ContainerAwareCommand extends Command
{
    public function getContainer(): Container
    {
        return $this->getApplication()->getContainer();
    }
}