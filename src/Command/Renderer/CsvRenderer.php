<?php
/**
 * Created by PhpStorm.
 * User: zoki
 * Date: 27/07/2018
 * Time: 11:41
 */

namespace BOF\Command\Renderer;

use Symfony\Component\Console\Output\OutputInterface;

class CsvRenderer implements Renderer
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function render(array $reportData)
    {
        $tableHeaderTemplate = array_map(function ($monthNum) {
            return $monthNum == 0 ? str_pad("Profile", 20) : date('M', mktime(null, null, null, $monthNum));
        }, range(0, 12));


        $this->output->writeln("CSV DATA SHOULD BE HERE");
    }
}