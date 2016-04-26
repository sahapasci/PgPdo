<?php

namespace PgPdo\Exception;

/**
 * Should be only thrown by objects which implements DaoInterface
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class PgPdoDaoException extends PgPdoException {
	private $statement;
	private $errorInfo;

	function __construct($message = null, $code = null) {

		parent::__construct($message, $code);
	
	}

	public function getErrorInfo() {

		return $this->errorInfo;
	
	}

	public function getStatement() {

		return $this->statement;
	
	}

	public function setErrorInfo($errorInfo) {

		$this->errorInfo = $errorInfo;
	
	}

	public function setStatement($statement) {

		$this->statement = $statement;
	
	}
}