<?php

namespace PgPdo\Dao;

/**
 * Objects which implement MapperInterface shouldn't access any db operations
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
interface MapperInterface {

	/**
	 * Implementations must implement this method to map each row to a new array or object.
	 * 
	 * @return The result object or array for the current row
	 * @param Array $row Row-array to be converted
	 * @param int $index The number of the current row
	 */
	public function mapRow($row, $index);
}