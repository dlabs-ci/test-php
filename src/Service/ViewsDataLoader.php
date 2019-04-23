<?php
namespace BOF\Service;

use Doctrine\DBAL\Connection;

abstract class ViewsDataLoader
{
	/**
	 * Db instance
	 * 
	 * @param \Doctrine\DBAL\Connection $db
	 */
	protected $db;

	/**
	 * Constructor
	 * 
	 * @param \Doctrine\DBAL\Connection $db
	 */
	public function __construct(Connection $db)
	{
		$this->db = $db;
	}

	/**
	 * Load data from the database
	 * 
	 * @return array
	 */
	abstract protected function load();

	/**
	 * Return a query build with applied query parameters
	 * 
	 * @return \Doctrine\DBAL\Driver\PDOStatement
	 */
	abstract protected function getQuery();
	
}
