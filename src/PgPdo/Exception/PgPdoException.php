<?php

namespace PgPdo\Exception;

use \RuntimeException;

/**
 * Marker exception for PgPdo library
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
abstract class PgPdoException extends RuntimeException {

	function __construct($message = null, $code = null, $previous = null) {

		parent::__construct($message, $code, $previous);
	
	}
}