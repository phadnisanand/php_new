<?php
declare(strict_types= 1);

class User {

	public function __construct(protected int|string $salary) {
	
	}
	
	public function display() {
			return $this->salary;
	}
}
$user = new User("100 rupee");
echo $user->display();