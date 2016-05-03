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

	public static function StatementCannotBeNull() {

		return new self("'statement' cannot be null.", 1);
	
	}

	public static function StatementSqlCannotBeNull() {

		return new self("'statement->sql' cannot be null.", 2);
	
	}

	public static function StatementParamsShouldBeStatementParamCollection() {

		return new self("'statement->params' should be an instance of StatementParamCollection.", 3);
	
	}

	public static function dbConnectionCannotBeNull() {

		return new self("'dbConnection' cannot be null.", 4);
	
	}

	public static function errorOccuredWhileBindingParam($statement, $errorInfo, $paramName) {

		$error = new self("Error occured while binding param '$paramName'.", 5);
		$error->setStatement($statement);
		$error->setErrorInfo($errorInfo);
		return $error;
	
	}

	public static function errorOccuredWhileExecutionStatement($statement, $errorInfo) {

		$error = new self('Error occured while executing statement.', 6);
		$error->setStatement($statement);
		$error->setErrorInfo($errorInfo);
		return $error;
	
	}

	public static function statementFinishedWithAnError($statement, $errorInfo) {

		$error = new self('Statement finished with an error.', 7);
		$error->setStatement($statement);
		$error->setErrorInfo($errorInfo);
		return $error;
	
	}

	public static function mapperShouldImplementMapperInterface() {

		return new self("'mapper' should be instance of Mapper interface.", 8);
	
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