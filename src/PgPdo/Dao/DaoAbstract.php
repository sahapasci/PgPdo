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
	private $fetchType = PDO::FETCH_ASSOC;

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
	 * Checks whether statement is ready for execution.
	 * 
	 * @param StatementInterface $statement Statement to be checked.
	 * @throws PgPdoDaoException
	 */
	protected function checkStatement(StatementInterface $statement) {

		if (!$statement) {
			throw PgPdoDaoException::StatementCannotBeNull();
		}
		
		if (!$statement->getSql()) {
			throw PgPdoDaoException::StatementSqlCannotBeNull();
		}
		
		if (!($statement->getParams() instanceof StatementParamCollection)) {
			throw PgPdoDaoException::StatementParamsShouldBeStatementParamCollection();
		}
		
		if (!$this->getDbConnection()) {
			throw PgPdoDaoException::dbConnectionCannotBeNull();
		}
	
	}

	/**
	 * Executes statement and returns the result according to statement type
	 * 
	 * @param StatementInterface $statement
	 */
	protected function execute(StatementInterface $statement) {

		$this->checkStatement($statement);
		
		$pdoStatement = $this->getDbConnection()->prepare($statement->getSql());
		$mapper = ($statement instanceof MappedStatementInterface) ? $statement->getMapper() : null;
		
		foreach ($statement->getParams() as $param) {
			$paramValue = $param->getValue();
			
			$bindSuccessful = $pdoStatement->bindParam($param->getName(), $paramValue, $param->getType());
			
			if (!$bindSuccessful) {
				throw PgPdoDaoException::errorOccuredWhileBindingParam($statement, $pdoStatement->errorInfo(), $param->getName());
			}
		}
		try {
			
			$executeSuccessful = $pdoStatement->execute();
		} catch (PDOException $e) {
			throw PgPdoDaoException::errorOccuredWhileExecutionStatement($statement, $pdoStatement->errorInfo());
		}
		
		if (!$executeSuccessful) {
			throw PgPdoDaoException::statementFinishedWithAnError($statement, $pdoStatement->errorInfo());
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
			throw PgPdoDaoException::mapperShouldImplementMapperInterface();
		}
	
	}

	protected function getStatementResultAsRecord(PDOStatement $pdoStatement, $mapper = null) {

		$row = $pdoStatement->fetch($this->getFetchType());
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
			while ($row = $pdoStatement->fetch($this->getFetchType())) {
				$mappedRow = $mapper->mapRow($row, $index);
				if ($mappedRow) {
					$recordSet[] = $mappedRow;
				}
				$index++;
			}
		} else {
			$recordSet = $pdoStatement->fetchAll($this->getFetchType());
		}
		if ($recordSet) {
			return $recordSet;
		}
		
		return null;
	
	}

	public function getFetchType() {

		return $this->fetchType;
	
	}

	/**
	 * Sets fetchType for executed statements.
	 * Default value is PDO::FETCH_ASSOC.
	 * 
	 * @param int $fetchType PDO::FETCH_*
	 */
	public function setFetchType($fetchType) {

		if ((int) $fetchType) {
			$this->fetchType = (int) $fetchType;
		}
	
	}
}