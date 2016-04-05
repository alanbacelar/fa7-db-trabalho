<?php

class PostgreSQLDBTest extends Test {
	private $specialties_create_table_sql;
	private $medicals_create_table_sql;

	protected function init() {
		$this->specialties_create_table_sql = '
			CREATE TABLE IF NOT EXISTS specialties (id serial primary key, name text);'
		;

		$this->medicals_create_table_sql = '
			CREATE TABLE IF NOT EXISTS medicals (id serial primary key, name text, specialty_id integer references specialties (id));
		';

	    $this->createTables();
	}

	private function createTables() {
		$this->execSQL($this->specialties_create_table_sql);
		$this->execSQL($this->medicals_create_table_sql);
	}

	protected function finish() {
		$this->execSQL('DROP TABLE medicals, specialties;');
	}
}

?>