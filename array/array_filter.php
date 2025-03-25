<?php
$arr = array(5, 10, 15, 20, 25);
/*$filterArr = array_filter($arr, fn($num)  => $num%2 ==0);
print_r($filterArr );*/

/*function filterArrFunc($num) {
	return  $num % 2 == 0;
}

$filterArr = array_filter($arr, 'filterArrFunc');
print_r($filterArr );*/

$filterArr = array_filter($arr, function($num) {
	return  $num % 2 == 0;
});
print_r($filterArr );