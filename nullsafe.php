<?php
class Profile {
	public function display() {
			return 'anand Phadns';
	}
}

class User {
	public function getData() {
		//return new Profile;
		return null;
	}
	
}

$user =new User();
echo $user?->getData()?->display() ?? 'no profile';

echo 'hi';