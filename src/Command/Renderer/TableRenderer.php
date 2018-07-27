<?php
/**
 * Created by PhpStorm.
 * User: zoki
 * Date: 27/07/2018
 * Time: 11:41
 */

namespace BOF\Command\Renderer;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

class TableRenderer implements Renderer
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

        $table = new Table($this->output);
        $table->setColumnWidths([24] + array_fill(0, 13, 9));
        $table->setColumnStyle(0, (new TableStyle())->setPadType(STR_PAD_RIGHT));
        $table->setStyle((new TableStyle())->setPadType(STR_PAD_LEFT));

        foreach ($reportData as $year => $viewData) {
            $header = $tableHeaderTemplate;
            $header[0] .= $year;

            $table->setRows([]); //remove all previous rows
            $table->setHeaders($header);
            $table->setRows($viewData);
            $table->render();
        }
    }
}