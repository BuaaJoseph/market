<?php
	
	include_once "HandleInput.php";
	$input = $_SERVER['argv'][1];

	$runner = new HandleInput();
	$runner->calculate($input);