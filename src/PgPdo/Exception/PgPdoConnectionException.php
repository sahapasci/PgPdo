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
}