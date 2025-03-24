<?php
class ABC {
	function func1() {
	  echo 'func 1' . '<br/>';
	  return $this;
	}

	function func2() {
	  echo 'func 2' . '<br/>';
	  return $this;
	}

	function func3() {
	  echo 'func 3';
		
	}
}
$obj = new ABC();

$obj->func1()->func2()->func3();