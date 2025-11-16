<?php
class Emp {
	
	//public $name;
	//public $age;
   	public function __construct(protected $name, protected $age) {
		//$this->name =$name;
		//$this->age = $age;
	}
}

$obj = new Emp('Anand', '35');
var_dump($obj);
