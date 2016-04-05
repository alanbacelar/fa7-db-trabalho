<?php

abstract class Test {

	protected $counter_registers = 1000000;
	protected $timer;
	protected $connection;
	protected $data;
	protected $limit = 1000;
	protected $search_string = '1000';


	public function __construct(Connection $connection, TimeHelper $timer) {
		$this->timer = $timer;
		$this->connection = $connection;
	}

	protected function init() {}
	protected function finish() {}

	protected function getDb() {
		return $this->connection->getConnection();
	}

	protected function execSQL($sql) {
		try {
			$this->getDb()->exec($sql);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	public function forOperator($fn) {
		$i = 0;
		for($i; $i < $this->counter_registers; $i++) {
            $fn($i);
        }
	}

	public function forUpdateOperator($fn) {
		foreach ( $this->data as $id => $value ) {
		    $fn($value, $id);
		}
	}

	protected function execute($description, $fn) {
		$this->timer->start($description);
		$this->$fn();
		$this->timer->stop();
	}

	public function run() {
		$this->init();

		$this->execute("Inserindo $this->counter_registers especialidades", 'inserting');
		$this->execute("Inserindo 1 medico", 'inserting2');
		$this->execute("Consultando $this->counter_registers de especialidades", 'query1');
		$this->execute("Consultando uma especialidade por ID", 'query2');
		$this->execute("Consultando uma especialidade por nome", 'query3');
		$this->execute("Consultando médicos com especialidades (JOIN)", 'query4');
		$this->execute("Consultando médicos com especialidades onde o ID da especialidade é 3", 'query5');
		$this->execute("Consultando médicos com especialidades onde o nome da especialidade contém 1000 (LIKE)", 'query6');
		$this->execute("Consultando a quatidade de especialidades (Agregação)", 'query7');
		$this->execute("Consultando a quatidade de médicos com especialidades (Agregação e JOIN)", 'query8');
		$this->execute("Consultando a quatidade de médicos com especialidades agrupado por nome (Agregação, JOIN e GROUPBY)", 'query9');
		$this->execute("Consultando especialidades com limit $this->limit", 'query10');
		$this->execute("Atualizando $this->counter_registers especialidades", 'updating');
		$this->execute("Removendo 1 medico(s)", 'deleting2');
		$this->execute("Removendo $this->counter_registers especialidades", 'deleting');

		$this->finish();
	}

	protected function inserting() {
		$this->forOperator(function($i) {
			$this->execSQL("INSERT INTO specialties (name) VALUES ('Specialty $i');");
		});
	}

	protected function inserting2() {
		$this->execSQL("INSERT INTO medicals (name, specialty_id) VALUES ('Mario', 1);");
	}

	protected function query1() {
		$this->data = $this->getDb()->query("SELECT * FROM specialties")->fetchAll();
	}

	protected function query2() {
		$this->execSQL("SELECT * FROM specialties WHERE id = 100");
	}

	protected function query3() {
		$this->execSQL("SELECT * FROM specialties WHERE name = 'Specialty 1000'");
	}

	protected function query4() {
		$this->execSQL("SELECT * FROM medicals t1 JOIN specialties t2 ON (t1.specialty_id = t2.id)");
	}

	protected function query5() {
		$this->execSQL("SELECT * FROM medicals t1 JOIN specialties t2 ON (t1.specialty_id = t2.id) WHERE t2.id = 300;");
	}

	protected function query6() {
		$this->execSQL("SELECT * FROM medicals t1 JOIN specialties t2 ON (t1.specialty_id = t2.id) WHERE t2.name LIKE '%{$this->search_string}%'");
	}

	protected function query7() {
		$this->execSQL("SELECT count(*) FROM specialties");
	}

	protected function query8() {
		$this->execSQL("SELECT count(t1.id) FROM medicals t1 JOIN specialties t2 ON (t1.specialty_id = t2.id)");
	}

	protected function query9() {
		$this->execSQL("SELECT count(*) FROM specialties GROUP BY name");
	}

	protected function query10() {
		$this->execSQL("SELECT * FROM specialties LIMIT $this->limit");
	}

	protected function updating() {
		$this->forUpdateOperator(function($data, $i) {
			$this->execSQL("UPDATE specialties SET name = '{$data['name']} updated' WHERE id = {$data['id']}");
		});
	}

	protected function deleting() {
		$this->execSQL("DELETE FROM specialties;");
	}

	protected function deleting2() {
		$this->execSQL("DELETE FROM medicals;");
	}

}

?>