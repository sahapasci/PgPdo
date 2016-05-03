<?php

namespace PgPdo\Connection;

use \PDO;
use PgPdo\Config\ConfigInterface;
use PgPdo\Exception\PgPdoConnectionException;

/**
 * DB connection object which wraps PDO object.
 * Autocommit is true till beginTransaction is called. Hovewer, before disconnect, if autocommit is false default action
 * is to commit.
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class DbConnection {
	private $config;
	private $pdo;

	function __construct(ConfigInterface $config = null) {

		$this->setConfig($config);
	
	}

	function __destruct() {

		$this->disconnect();
	
	}

	public function getPdo() {

		return $this->pdo;
	
	}

	private function setPdo(PDO $pdo) {

		$this->pdo = $pdo;
	
	}

	private function checkConnectionState() {

		if ($this->isActive()) {
			throw PgPdoConnectionException::connectionIsAlreadyActive();
		}
		
		if (!$this->getConfig()) {
			throw PgPdoConnectionException::configCannotBeNull();
		}
		
		if (!$this->getConfig()->getDns()) {
			throw PgPdoConnectionException::configDnsCannotBeNull();
		}
	
	}

	/**
	 *
	 * @throws \PDOException
	 */
	public function connect() {

		$this->checkConnectionState();
		
		$dns = $this->getConfig()->getDns();
		$username = $this->getConfig()->getUserName();
		$passwd = $this->getConfig()->getPassword();
		
		try {
			$pdo = new PDO($dns, $username, $passwd);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->setPdo($pdo);
		} catch (PDOException $e) {
			throw PgPdoConnectionException::cannotConnect($e);
		}
	
	}

	public function beginTransaction() {

		if ($this->inTransaction()) {
			throw PgPdoConnectionException::connectionIsAlreadyInTransaction();
		}
		return $this->getPdo()->beginTransaction();
	
	}

	public function disconnect() {

		if ($this->inTransaction()) {
			$this->commit();
		}
		$this->pdo = null;
	
	}

	public function getConfig() {

		return $this->config;
	
	}

	public function setConfig(ConfigInterface $config) {

		if ($this->getPdo()) {
			throw PgPdoConnectionException::configCannotBeChangedWhileDbIsActive();
		}
		
		$this->config = $config;
	
	}

	public function commit() {

		if (!$this->getPdo()) {
			throw PgPdoConnectionException::cannotCommitDbIsNotActive();
		}
		
		return $this->getPdo()->commit();
	
	}

	public function rollBack() {

		if (!$this->getPdo()) {
			throw PgPdoConnectionException::cannotRollbackDbIsNotActive();
		}
		
		return $this->getPdo()->rollBack();
	
	}

	public function inTransaction() {

		if (!$this->getPdo()) {
			return false;
		}
		
		return $this->getPdo()->inTransaction();
	
	}

	public function prepare($statement) {

		if (!$this->getPdo()) {
			throw PgPdoConnectionException::cannotPrepareDbIsNotActive();
		}
		
		return $this->getPdo()->prepare($statement);
	
	}

	public function isActive() {

		return ($this->getPdo() != null);
	
	}
}