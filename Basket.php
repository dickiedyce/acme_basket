<?php

// A self-contained PHP class for storing a shopping basket of goods, 
// handling shipping costs, and calculating totals.
class basket
{
	private $basket;
	
	private $catalogue;
	private $offers;
	private $chargeRules;
	
	private $names;
	private $prices;
			
	function __construct($_catalogue, $_chargeRules, $_offers) {
			$this->basket = [];
			$this->names = [];
			$this->prices = [];
			
			$this->catalogue = json_decode($_catalogue);
			foreach ($this->catalogue as $i => $details) {
				$code = $details->code;
				$this->names[$code] = $details->name;
				$this->prices[$code] = $details->price;
			}
			
			$this->chargeRules = json_decode($_chargeRules,true);
			$offerList =  json_decode($_offers);
			foreach ($offerList as $i => $details) {
				$code = $details->code;
				$this->offers[$code] = $details;
			}
			$this->basket = [];
			$this->shipping = 0.0;
	}
	
	public function __toString()
	{
		$output = "\n---------\nBasket contains:\n";
		foreach ($this->basket as $code => $qty) {
			$line = number_format($this->linetotal($code,$qty),2);
			$output .= "  $qty x $code {$this->names[$code]} \${$line}\n";
		}
		$total = number_format($this->total(),2);
		$shipping = number_format($this->calcShipping($subtotal),2);
		$output .= "  Shipping = \${$shipping}\n";
		$output .= "     Total = \${$total}\n";
		return $output;
	}
	
	private function calcShipping($total)
	{
		$shipping = 4.95; // added a default in case rules supplied poorly written
		foreach ($this->chargeRules as $ceiling => $charge) {
			if ($total <= $ceiling) {
				$shipping = $charge;
				break;
			}
		}
		return $shipping;
	}
	
	private function linetotal($code,$qty)
	{
		$price = $this->prices[$code];
		if (! is_null($this->offers[$code])) {
			switch ($this->offers[$code]->ruleType) {
				case "discountPerMultiple":
					$multiple = $this->offers[$code]->multiple;
					$discount = $this->offers[$code]->discount;
					$inOffer = floor($qty/$multiple)*$multiple;
					$outOfOffer = $qty - $inOffer;
					$subtotal = ($price * $outOfOffer) + ($price * $inOffer * (1.0-$discount));
					break;
				default:
					$subtotal = $price * $qty;
					break;
			}
		} else {
			$subtotal = $price * $qty;
		}
		return round( $subtotal, 2 , PHP_ROUND_HALF_DOWN);
	}
	
	public function total()
	{
		$total = 0.0;
		foreach ($this->basket as $code => $qty) {
			
			$total += $this->linetotal($code,$qty);
			
		}
		return round ($total + $this->calcShipping($total), 2, PHP_ROUND_HALF_DOWN);
	}
	
	public function add($code) 
	{
		$this->basket[$code] += 1;
	}

	public function addMultiple($code,$qty) 
	{
		$this->basket[$code] = $this->basket[$code] + $qty;
	}

	public function remove($code) 
	{
		if ($this->basket[$code] > 1) $this->basket[$code] -= 1;
	}
	
	public function removeLineitem($code) 
	{
		unset($this->basket[$code]);
	}
}

?>