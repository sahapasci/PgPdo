<?php

namespace PgPdo\Config;

/**
 * Configuration object for PostgreSQL
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class PgSqlConfig extends ConfigAbstract {
	private $databaseName;
	private $host;
	private $port = 5432;

	public function getDatabaseName() {

		return $this->databaseName;
	
	}

	public function getDns() {

		$host = $this->getHost();
		$port = $this->getPort();
		$databaseName = $this->getDatabaseName();
		
		$dns = "pgsql:host=$host port=$port dbname=$databaseName";
		return $dns;
	
	}

	public function getHost() {

		return $this->host;
	
	}

	public function getPort() {

		return $this->port;
	
	}

	public function setDatabaseName($databaseName) {

		$this->databaseName = $databaseName;
	
	}

	public function setHost($host) {

		$this->host = $host;
	
	}

	public function setPort($port) {

		$this->port = $port;
	
	}
}