<?php

namespace PgPdo\Dao;

use \PDO;

/**
 * Mostly you don't need to use this class directly.
 * Instead, use \Dal\Dao\StatementParamCollection->addParam.
 * 
 * @license Apache-2.0
 * @author Sahap Asci
 */
class StatementParam {
	private $name;
	private $value;
	private $type = PDO::PARAM_STR;

	function __construct($name = null, $value = null, $type = null) {

		$this->setName($name);
		$this->setValue($value);
		$this->setType($type);
	
	}

	public function getName() {

		return $this->name;
	
	}

	public function getType() {

		return $this->type;
	
	}

	public function getValue() {

		return $this->value;
	
	}

	public function setName($name) {

		$this->name = $name;
	
	}

	/**
	 * PDO::PARAM_*
	 * 
	 * @param int $type
	 */
	public function setType($type) {

		$this->type = $type;
	
	}

	public function setValue($value) {

		$this->value = $value;
	
	}
}