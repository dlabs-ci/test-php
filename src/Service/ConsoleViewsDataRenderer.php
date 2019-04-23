<?php
namespace BOF\Service;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleViewsDataRenderer extends ViewsDataRenderer
{
	/**
	 * Table header row
	 * 
	 * @param array
	 */
	protected $header;

	/**
	 * Return header array
	 * 
	 * @return array
	 */
	public function getHeader()
	{
		return $this->header;
	}

	/**
	 * Set table header
	 * 
	 * @param array $header
	 * @return this
	 */
	public function setHeader($header)
	{
		$this->header = $header;

		return $this;
	}

	/**
	 * Output the provided data 
	 * in console table format.
	 * 
	 * @return void
	 */
	public function render(SymfonyStyle $io, array $viewsData)
	{
		if(empty($viewsData)) {
			$io->writeln('n/a');
			return;
        }

				$table = new Table($io);
				
				if($this->getHeader()) {
					$table->setHeaders($this->getHeader());
				}
        
        foreach($viewsData as $viewData) 
        {
            $table->addRow(
				$this->formatRow($viewData)
			);
        }

        $table->render();
	}
}
