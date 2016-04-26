<?php

namespace PgPdo\Dao;

/**
 *
 * @license Apache-2.0
 * @author Sahap Asci
 */
interface MappedStatementInterface extends StatementInterface {

	/**
	 *
	 * @return Mapper
	 */
	public function getMapper();
}