<?php

namespace PgPdo\Config;

/**
 * Configuration object for connecting databases should implement this interface.
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
interface ConfigInterface {

	public function getUserName();

	public function getPassword();

	public function getDns();
}