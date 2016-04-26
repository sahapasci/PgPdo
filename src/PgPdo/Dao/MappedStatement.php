<?php

namespace PgPdo\Dao;

use PgPdo\Dao\Statement;
use PgPdo\Dao\MappedStatementInterface;

/**
 * @license Apache-2.0
 * @author Sahap Asci
 *
 */
class MappedStatement extends Statement implements MappedStatementInterface {
	private $mapper;

	public function __construct($sql = null, $returnType = null, StatementParamCollection $params = null) {

		parent::__construct($sql, $returnType, $params);
	
	}

	public function getMapper() {

		return $this->mapper;
	
	}

	public function setMapper($mapper) {

		$this->mapper = $mapper;
	
	}
}