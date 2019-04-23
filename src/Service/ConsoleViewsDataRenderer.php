<?php
namespace BOF\Service;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;

class ConsoleViewsDataRenderer extends ViewsDataRenderer
{
	/**
	 * Output the provided data 
	 * in console table format.
	 * 
	 * @return void
	 */
	public function render($io, $viewsData)
	{
		if(empty($viewsData)) {
			$io->writeln('n/a');
			return;
        }

        $table = new Table($io);
        $table
            ->setHeaders(['Profiles', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'])
        ;
        
        foreach($viewsData as $viewData) 
        {
            $table->addRow(
				$this->formatRow($viewData)
			);
        }

        $table->render();
	}
}
