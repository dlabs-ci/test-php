<?php
namespace BOF\Service;

abstract class ViewsDataRenderer
{
	/**
	 * Format the data by replacing empty fields
	 * with 'n/a' and format the numbers.
	 * 
	 * @param array $row
	 * 
	 * @return array
	 */
	protected function formatRow($row) 
	{
		return array_map (function($value) {
			if(is_numeric($value)) {
				$value = number_format($value, 0, '', ',');
			}
			return $value ?? 'n/a';
		}, $row);
	}
}
