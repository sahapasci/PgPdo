<?php

namespace PgPdo\Dao;

use \PDO;
use \PDOStatement;
use PgPdo\Connection\DbConnection;
use PgPdo\Exception\PgPdoDaoException;

/**
 *
 * @license Apache-2.0
 * @author Sahap Asci
 */
abstract class DaoAbstract implements DaoInterface {
	private $dbConnection;

	function __construct(DbConnection $dbConnection) {

		$this->setDbConnection($dbConnection);
	
	}

	protected function getDbConnection() {

		return $this->dbConnection;
	
	}

	private function setDbConnection(DbConnection $dbConnection) {

		$this->dbConnection = $dbConnection;
	
	}

	/**
	 * Executes statement and returns the result according to statement type
	 * 
	 * @param StatementInterface $statement
	 */
	protected function execute(StatementInterface $statement) {

		if (!$statement) {
			throw new PgPdoDaoException("'statement' cannot be null.", 3);
		}
		
		if (!$statement->getSql()) {
			throw new PgPdoDaoException("'statement->sql' cannot be null.", 3);
		}
		
		if (!($statement->getParams() instanceof StatementParamCollection)) {
			throw new PgPdoDaoException("'statement->params' should be an instance of StatementParamCollection.", 4);
		}
		
		if (!$this->getDbConnection()) {
			throw new PgPdoDaoException("'dbConnection' cannot be null.", 5);
		}
		
		$pdoStatement = $this->getDbConnection()->prepare($statement->getSql());
		$mapper = ($statement instanceof MappedStatementInterface) ? $statement->getMapper() : null;
		
		foreach ($statement->getParams() as $param) {
			$paramValue = $param->getValue();
			
			$bindSuccessful = $pdoStatement->bindParam($param->getName(), $paramValue, $param->getType());
			
			if (!$bindSuccessful) {
				$daoException = new PgPdoDaoException("Error occured while binding param '{$param->getName()}'.", 1);
				$daoException->setStatement($statement);
				$daoException->setErrorInfo($pdoStatement->errorInfo());
				throw $daoException;
			}
		}
		try {
			
			$executeSuccessful = $pdoStatement->execute();
		} catch (PDOException $e) {
			$daoException = new PgPdoDaoException('Error occured while executing statement.', 6);
			$daoException->setStatement($statement);
			$daoException->setErrorInfo($pdoStatement->errorInfo());
			throw $daoException;
		}
		
		if (!$executeSuccessful) {
			$daoException = new PgPdoDaoException('Error occured while executing statement.', 2);
			$daoException->setStatement($statement);
			$daoException->setErrorInfo($pdoStatement->errorInfo());
			throw $daoException;
		}
		
		switch ($statement->getReturnType()) {
			case StatementInterface::RETURNS_NOTHING :
				$result = null;
				break;
			case StatementInterface::RETURNS_AFFECTED_ROWS :
				$result = $pdoStatement->rowCount();
				break;
			case StatementInterface::RETURNS_SINGLE_VALUE :
				$result = $this->getStatementResultAsSingleValue($pdoStatement);
				break;
			case StatementInterface::RETURNS_SINGLE_RECORD :
				$result = $this->getStatementResultAsRecord($pdoStatement, $mapper);
				break;
			
			default : // StatementInterface::RETURNS_SET_OF_RECORDS :
				$result = $this->getStatementResultAsRecordSet($pdoStatement, $mapper);
				break;
		}
		
		return $result;
	
	}

	protected function getStatementResultAsSingleValue(PDOStatement $pdoStatement) {

		$row = $pdoStatement->fetch(PDO::FETCH_NUM);
		if ($row) {
			return $row[0];
		}
		
		return null;
	
	}

	private function checkMapper($mapper) {

		if (!($mapper instanceof MapperInterface)) {
			throw new PgPdoDaoException("'mapper' should be instance of Mapper interface.", 7);
		}
	
	}

	protected function getStatementResultAsRecord(PDOStatement $pdoStatement, $mapper = null) {

		$row = $pdoStatement->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			if ($mapper) {
				$this->checkMapper($mapper);
				$mappedRow = $mapper->mapRow($row, $index);
				return $mappedRow;
			} else {
				return $row;
			}
		}
		
		return null;
	
	}

	protected function getStatementResultAsRecordSet(PDOStatement $pdoStatement, $mapper = null) {

		if ($mapper) {
			$this->checkMapper($mapper);
			$index = 0;
			$recordSet = array();
			while ($row = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
				$mappedRow = $mapper->mapRow($row, $index);
				if ($mappedRow) {
					$recordSet[] = $mappedRow;
				}
				$index++;
			}
		} else {
			$recordSet = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
		}
		if ($recordSet) {
			return $recordSet;
		}
		
		return null;
	
	}
}