<?php

namespace PgPdo\Connection;

use \PDO;
use \PgPdo\Config\ConfigInterface;
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

	private function checkConnection($message, $code) {

		if (!$this->getPdo()) {
			throw new PgPdoConnectionException($message, $code);
		}
	
	}

	/**
	 *
	 * @throws \PDOException
	 */
	public function connect() {

		if ($this->isActive()) {
			throw new PgPdoConnectionException('DB connection is already active."', 5);
		}
		
		if (!$this->getConfig()) {
			throw new PgPdoConnectionException('"config" cannot be null', 8);
		}
		
		$dns = $this->getConfig()->getDns();
		$username = $this->getConfig()->getUserName();
		$passwd = $this->getConfig()->getPassword();
		
		try {
			$pdo = new PDO($dns, $username, $passwd);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->setPdo($pdo);
		} catch (PDOException $e) {
			throw new PgPdoConnectionException('Cannot connect DB', 1, $e);
		}
	
	}

	public function beginTransaction() {

		if ($this->inTransaction()) {
			throw new PgPdoConnectionException('DB connection is already in transaction active."', 6);
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
			throw new PgPdoConnectionException('"config" cannot be changed while db connection is active."', 2);
		}
		
		$this->config = $config;
	
	}

	public function commit() {

		$this->checkConnection('Cannot "commit" because DB connection is not active.', 3);
		
		return $this->getPdo()->commit();
	
	}

	public function rollBack() {

		$this->checkConnection('Cannot "rollback" because DB connection is not active.', 4);
		
		return $this->getPdo()->rollBack();
	
	}

	public function inTransaction() {

		if (!$this->getPdo()) {
			return false;
		}
		
		return $this->getPdo()->inTransaction();
	
	}

	public function prepare($statement) {

		$this->checkConnection('Cannot "prepare" because DB connection is not active.', 7);
		
		return $this->getPdo()->prepare($statement);
	
	}

	public function isActive() {

		return ($this->getPdo() != null);
	
	}
}