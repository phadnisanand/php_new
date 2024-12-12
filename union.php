<?php
// https://php.watch/versions/8.0/union-types
class Example {
    private int|float $foo;
    public function squareAndAdd(float|int $bar, $foo): int|float {
        return $bar ** 2 + $foo;
    }
}
$obj = new Example();
echo $obj->squareAndAdd(10.3, foo:20);  echo '<br />';
echo $obj->squareAndAdd(10,foo:20);