<?php
// mixed type 
// function never return value.
function dd(mixed $input): never
{
    echo $input;
	header('Location: https://google.com', true, $permanent ? 301 : 302);
    exit;
}

dd('hi coming');
