<?php
namespace BOF\Service;

use Doctrine\DBAL\Connection;

class YearlyViewsDataLoader extends ViewsDataLoader
{
	/**
	 * Year for which the data is loaded
	 * 
	 * @param int
	 */
	protected $year;

	/**
	 * @see ViewsDataLoader::load
	 */
	public function load()
	{
		return $this
			->getQuery()
			->fetchAll();
	}

	/**
	 * @see ViewsDataLoader::getQuery
	 */
	protected function getQuery()
	{
		/**
		 * Get total count of user views for  
		 * selected year grouped by user and month.
		 */
		return $this->db
			->query("
				select 
					p.profile_name,
					{$this->caseMonths()}
					from profiles p
				left join 
					views v
				on 
					p.profile_id = v.profile_id
				where 
					YEAR(v.date) = {$this->getYear()}
				group by 
					v.profile_id
				order 
					by p.profile_name
			");
	}

	/**
	 * Case views months so the groups are displayed horizontally
	 * 
	 * @return string
	 */
	protected function caseMonths()
	{
		$months = '';

		foreach(range(1,12) as $month) {
			$months.= "sum(CASE WHEN month(v.date) = {$month} THEN v.views END) AS month_{$month}, ";
		}

		return rtrim($months,', ');
	}

	/**
	 * Set year
	 * 
	 * @param int $year
	 * @return this
	 */
	public function setYear($year)
	{
		$this->year = $year;

		return $this;
	}

	/**
	 * Get year, if null default to current year
	 * 
	 * @return int $year
	 */
	public function getYear()
	{
		return $this->year 
			?? $this->year = date('Y');
	}
}
