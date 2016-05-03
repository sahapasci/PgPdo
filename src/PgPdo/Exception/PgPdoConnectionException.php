<?php

namespace PgPdo\Exception;

/**
 * This type of exceptions are thrown by '\PgPdo\Connection' namespace objects
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class PgPdoConnectionException extends PgPdoException {

	function __construct($message = null, $code = null, $previous = null) {

		parent::__construct($message, $code, $previous);
	
	}

	private static function connectionIsNotActive($action, $errorCode) {

		return new self('Cannot "' . $action . '" because DB connection is not active.', $errorCode);
	
	}

	public static function cannotRollbackDbIsNotActive() {

		return self::connectionIsNotActive('rollback', 1);
	
	}

	public static function cannotCommitDbIsNotActive() {

		return self::connectionIsNotActive('commit', 2);
	
	}

	public static function cannotPrepareDbIsNotActive() {

		return self::connectionIsNotActive('prepare', 3);
	
	}

	public static function configCannotBeChangedWhileDbIsActive() {

		return new self('"config" cannot be changed while db connection is active.', 4);
	
	}

	public static function connectionIsAlreadyInTransaction() {

		return new self('DB connection is already in transaction.', 5);
	
	}

	public static function cannotConnect(PDOException $previousError) {

		return new self('Cannot connect to database', 6, $previousError);
	
	}

	public static function configDnsCannotBeNull() {

		return new self('config->dns cannot be null', 7);
	
	}

	public static function connectionIsAlreadyActive() {

		return new self('DB connection is already active.', 8);
	
	}

	public static function configCannotBeNull() {

		return new self('"config" cannot be null', 9);
	
	}
}