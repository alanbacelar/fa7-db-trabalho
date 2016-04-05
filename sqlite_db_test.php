<?php

class SQLiteDBTest extends Test {
	private $specialties_create_table_sql;
	private $medicals_create_table_sql;

	protected function init() {
		$this->specialties_create_table_sql = '
			CREATE TABLE specialties (id integer primary key autoincrement, name text);'
		;

		$this->medicals_create_table_sql = '
			CREATE TABLE medicals (id integer primary key autoincrement, name text, specialty_id integer references specialties (id));
		';

	    $this->createTables();
	}

	private function createTables() {
		$this->execSQL($this->specialties_create_table_sql);
		$this->execSQL($this->medicals_create_table_sql);
	}
}

?>