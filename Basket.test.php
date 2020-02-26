<?php
	require('basket.php');
	require('testdata.php');
	
	echo "Test Run for Basket class\n";

	$bar = new basket($catalogueJSON,$chargeRulesJSON,$offersJSON );
	$bar->add("B01");
	$bar->add("G01");
	echo $bar;

	$bar->removeLineitem("B01");
	$bar->removeLineitem("G01");
	$bar->add("R01");
	$bar->add("R01");
	echo $bar;

	$bar->removeLineitem("R01");
	$bar->add("R01");
	$bar->add("G01");
	echo $bar;

	$bar->removeLineitem("R01");
	$bar->removeLineitem("G01");
	$bar->add("B01");
	$bar->add("B01");
	$bar->add("R01");
	$bar->add("R01");
	$bar->add("R01");
	echo $bar;

?>