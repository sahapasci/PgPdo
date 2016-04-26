<?php

namespace PgPdo\Config;

/**
 *
 * @license Apache-2.0
 * @author Sahap Asci
 */
abstract class ConfigAbstract implements ConfigInterface {
	private $userName;
	private $password;

	public function getUserName() {

		return $this->userName;
	
	}

	public function setUserName($userName) {

		$this->userName = $userName;
	
	}

	public function getPassword() {

		return $this->password;
	
	}

	public function setPassword($password) {

		$this->password = $password;
	
	}

	abstract public function getDns();
}