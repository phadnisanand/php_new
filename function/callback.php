<?php

function mainfunc($func) {
	$a =10;
	/*return function() use($a) {
		return $a;
	};*/
	$func($a);
};

function display($a){
	echo $a;
}


$abc= mainfunc('display');
//print_r($abc());