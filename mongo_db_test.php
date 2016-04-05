<?php

class MongoDBTest extends Test {

	protected function getDb() {
		return parent::getDb()->trabalho;
	}

	public function getMedicals() {
		return $this->getDb()->medicals;
	}
	
	public function getSpecialties() {
		return $this->getDb()->specialties;
	}

	public function inserting() {
		$this->forOperator(function($i) {
			$this->getSpecialties()->insert( ['name' => 'Specialty' . $i] );
		});
	}

	public function inserting2() {
		$this->getMedicals()->insert( ['name' => 'Medical 1', 'specialty' => ['name' => 'Specialty1'] ] );
	}

	function query1() {
		$this->data = $this->getSpecialties()->find();
	}

	function query2() {
		$this->getSpecialties()->find(['id' => 100]);
	}

	function query3() {
		$this->getSpecialties()->find(['nome' => 'Specialty4']);
	}

	function query4() {
		$this->getMedicals()->find();
	}

	function query5() {
		$this->getMedicals()->find(['specialty.id' => 3]);
	}

	function query6() {
		$this->getSpecialties()->find(['name' => new MongoRegex("/^$this->search_string/i")]);
	}

	function query7() {
		$this->getSpecialties()->count();
	}

	function query8() {
		$this->getMedicals()->count();
	}

	function query9() {
		$this->getSpecialties()->aggregate(
			[
				[
					'$group' => [
		            	'_id' => ['name' => '$name'],
	        		]
	        	]
			], 
			['allowDiskUse' => true, 'explain' => true]
		);
	}

	function query10() {
		$cursor = $this->getSpecialties()->find()->limit($this->limit);
	}

	function updating() {
		$this->forUpdateOperator(function($data, $i) {
			$this->getSpecialties()->update($data, ['name' => 'Specialty Updated ' . $i]);
		});
		
	}

	function deleting() {
		$this->getSpecialties()->remove();
	}

	function deleting2() {
		$this->getMedicals()->remove();
	}

}

?>