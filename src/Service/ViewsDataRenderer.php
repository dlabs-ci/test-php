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

	/**
	 * Return header array
	 * 
	 * @return array
	 */
	protected function getHeader()
	{
		return ['Profiles', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'];
	}
}
