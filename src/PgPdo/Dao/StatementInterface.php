<?php

namespace PgPdo\Dao;

/**
 *
 * @license Apache-2.0
 * @author Sahap Asci
 */
interface StatementInterface {
	
	/**
	 * Query won't return any result (returns always null).
	 */
	const RETURNS_NOTHING = 1;
	
	/**
	 * Query result is single value or null.
	 */
	const RETURNS_SINGLE_VALUE = 2;
	
	/**
	 * Query result is an named array which key is column and value is column value or null.
	 */
	const RETURNS_SINGLE_RECORD = 3;
	
	/**
	 * Query result is an named array which key is column and value is column value or null.
	 */
	const RETURNS_SET_OF_RECORDS = 4;
	
	/**
	 * Query does not return any result however affected rows are needed.
	 */
	const RETURNS_AFFECTED_ROWS = 5;

	/**
	 * This should not return null
	 * 
	 * @return Returns collection of params in type of \Dal\Dao\StatementParamCollection
	 */
	public function getParams();

	/**
	 * Returns the return type of the statement after execution.
	 * 
	 * @return int \Dal\Dao\StatementInterface::RETURNS_*
	 */
	public function getReturnType();

	/**
	 *
	 * @return sql string to be executed.
	 */
	public function getSql();
}


