<?php

namespace PgPdo\Dao;

/**
 * Stores sql, parameters and return type to create an actual PDOStatement and binding parameter values
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class Statement implements StatementInterface {
	private $params;
	private $sql;
	private $returnType;

	/**
	 *
	 * @param string $sql
	 * @param int $returnType Return type of the statement after execution in type of
	 *        	\Dal\Dao\StatementInterface::RETURNS_*
	 * @param StatementParamCollection $params
	 */
	function __construct($sql = null, $returnType = null, StatementParamCollection $params = null) {

		if ($params == null) {
			$params = new StatementParamCollection();
		}
		
		$this->setParams($params);
		$this->setSql($sql);
		$this->setReturnType($returnType);
	
	}

	public function getParams() {

		return $this->params;
	
	}

	public function getSql() {

		return $this->sql;
	
	}

	protected function setParams(StatementParamCollection $statementParamCollection) {

		$this->params = $statementParamCollection;
	
	}

	public function setSql($sql) {

		$this->sql = $sql;
	
	}

	public function getReturnType() {

		return $this->returnType;
	
	}

	public function setReturnType($returnType) {

		$this->returnType = $returnType;
	
	}
}