<?php
// destructuring
[$a,  ,  ,  ,$c] = [10, 20, 30, 40, 50];
echo $a . ' '  . $c;


function variable(...$arr) {
	echo '<pre>'; print_r($arr); 
}
variable(10,20,30, 40,50);


function variable2($single) {
  echo $single;
}
$arr = [10, 20, 30, 40, 50];
variable2(...$arr);
echo '<br />';
function printfunc($a, $d, $c=100) {
	echo $d;
}
printfunc(d:200, a:100);