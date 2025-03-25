<?php
// https://www.php.net/manual/en/functions.anonymous.php
// https://www.w3schools.com/php/func_array_reduce.asp
$invoiceItems = [
	['price' => 10, 'qty' => 3, 'desc' => 'Item 1'],
	['price' => 20, 'qty' => 3, 'desc' => 'Item 2'],
	['price' => 30, 'qty' => 3, 'desc' => 'Item 3'],
	['price' => 40, 'qty' => 3, 'desc' => 'Item 4'],
	['price' => 50, 'qty' => 3, 'desc' => 'Item 5']
];

/*function myFunction($prev,$current) {
	return  $prev + $current['price'];
}*/

/*$sum = array_reduce($invoiceItems, "myFunction", 0 );
echo $sum;*/

// lamda function
/*$sum = array_reduce($invoiceItems, function ($prev, $current) {
	return $prev + $current['price'];
}, 0);
echo $sum;*/

// arrow function
$sum = array_reduce($invoiceItems, fn($sum, $item) => $sum + $item['price'], 0);
echo $sum; 