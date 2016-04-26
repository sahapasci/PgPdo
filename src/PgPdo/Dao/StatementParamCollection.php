<?php

namespace PgPdo\Dao;

use \PDO;
use \Iterator;

/**
 *
 * @license Apache-2.0
 * @author Sahap Asci
 */
class StatementParamCollection implements Iterator {
	const PARAM_NULL = 0;
	const PARAM_INT = 1;
	/**
	 * String parameter type (Default)
	 */
	const PARAM_STR = 2;
	const PARAM_BOOL = 5;
	const PARAM_AUTO = -1;
	private $position = 0;
	private $collection = array();

	function __construct() {

	
	}

	public function addStatementParam(StatementParam $param) {

		$this->collection[] = $param;
	
	}

	public function getCount() {

		return count($this->collection);
	
	}

	/**
	 *
	 * @param string $name Param Name
	 * @param string $value Param Value
	 * @param string $type StatementParamCollection::PARAM_*
	 */
	public function addParam($name, $value = null, $type = self::PARAM_AUTO) {

		switch ($type) {
			case self::PARAM_AUTO :
				$pdoType = $this->getPdoType($value);
				break;
			case self::PARAM_NULL :
				$pdoType = PDO::PARAM_NULL;
				break;
			case self::PARAM_INT :
				$pdoType = PDO::PARAM_INT;
				break;
			case self::PARAM_BOOL :
				$pdoType = PDO::PARAM_BOOL;
				break;
			default :
				$pdoType = PDO::PARAM_STR;
		}
		
		$statementParam = new StatementParam($name, $value, $pdoType);
		$this->collection[] = $statementParam;
	
	}

	/**
	 *
	 * @param string $value Param Value
	 * @return number
	 */
	protected function getPdoType($value) {

		if (is_null($value)) {
			return PDO::PARAM_NULL;
		} else if (is_int($value)) {
			return PDO::PARAM_INT;
		} else if (is_bool($value)) {
			return PDO::PARAM_BOOL;
		} else {
			return PDO::PARAM_STR;
		}
	
	}

	public function current() {

		return $this->collection[$this->position];
	
	}

	public function next() {

		++$this->position;
	
	}

	public function key() {

		return $this->position;
	
	}

	public function valid() {

		return isset($this->collection[$this->position]);
	
	}

	public function rewind() {

		$this->position = 0;
	
	}
}



