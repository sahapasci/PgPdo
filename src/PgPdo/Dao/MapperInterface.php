<?php

namespace PgPdo\Dao;

/**
 *
 * @license Apache-2.0
 * @author Sahap Asci
 */
interface MapperInterface {

	public function mapRow($row, $index);
}