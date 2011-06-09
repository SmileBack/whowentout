<?php

class Test {
	static $n = 0;
	
	function __construct() {
		//$this->id = Test::$n;
		$this->id = ++Test::$n;
	}
}

function testRef($a) {
	print $a->id;
}

$test = new Test();
testRef($test);

$test = new Test();
testRef($test);

$test = new Test();
testRef($test);
