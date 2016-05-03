<?php

namespace PgPdo\Dao;

use PgPdo\Connection\DbConnectionFactory;

/**
 * Default implementation of DaoAbstract which uses default connection
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class DefaultDao extends DaoAbstract {

	public function __construct() {

		parent::__construct(DbConnectionFactory::getDefaultConnection());
	
	}

	public function getDbConnection() {

		return parent::getDbConnection();
	
	}

	public function execute(StatementInterface $statement) {

		return parent::execute($statement);
	
	}
}