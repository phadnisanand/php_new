<?php
class Example {
    public function __construct(private $fN, private $sN) {
	}
	
	public function displayData() {
		echo $this->fN .  '  '  . $this->sN;
	}
}

$obj = new Example('Anand' ,  'S');
$obj->displayData();
