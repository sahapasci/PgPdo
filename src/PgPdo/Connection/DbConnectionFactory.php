<?php

namespace PgPdo\Connection;

use \PgPdo\Config\ConfigInterface;
use \PgPdo\Config\PgSqlConfig;

/**
 * Singleton factory class for creating DbConnection instance
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class DbConnectionFactory {
	private static $defaultConfig = null;
	private static $defaultConnection = null;

	public static function getNewConnection(ConfigInterface $config) {

		$dbConnection = new DbConnection($config);
		return $dbConnection;
	
	}

	public static function getDefaultConnection() {

		if (!self::$defaultConnection) {
			self::$defaultConnection = self::getNewConnection(self::getDefaultConnectionConfiguration());
		}
		return self::$defaultConnection;
	
	}

	public static function getDefaultConnectionConfiguration() {

		return self::$defaultConfig;
	
	}

	public static function setDefaultConnectionConfiguration(ConfigInterface $config) {

		if (!self::$defaultConfig) {
			self::$defaultConfig = $config;
		} else {
			throw new DbConnectionException('Default configuration is already set.', 8);
		}
	
	}
}