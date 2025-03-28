<?php
class ABC {
		public function __construct(private $num, private $name) {}
		
		public function display() {
				echo $this->num . '  ' . $this->name;
		}
}
$abc = new ABC(10044, 'anand');
$abc->display();