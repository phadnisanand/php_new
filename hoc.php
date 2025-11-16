<?php

$users = [
	[ 'id'=>1, 'name' => 'Anand Phadnis', 'role' => 'admin'],
	[ 'id'=> 2, 'name' => 'Nutan Phadnis', 'role' => 'super'],
	[ 'id'=>3, 'name' => 'AAI Phadnis', 'role' => 'admin'],
];

function createFilter($key, $value) {
	return fn($item) => $item[$key] == $value;
}

$isAdmin = createFilter('role','admin');
$admins= array_filter($users, $isAdmin);
print_r($admins);