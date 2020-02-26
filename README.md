# Basket Class for Acme Widget Co

`$bar = new basket ( $catalogue, $rules, $offers );`

A self-contained PHP class for storing a shopping basket of goods, handling shipping costs, and calculating totals.

## Parameters

catalogue
: A JSON array, formatted as string, containing the codes, names, and prices available to be added to the basket.

Example JSON Structure:

````JSON
[
  {"code":"R01","name":"Red Widget","price":32.95},
  {"code":"G01","name":"Green Widget","price":24.95},
  {"code":"B01","name":"Blue Widget","price":7.95}
]
````
	
rules
: A JSON object, formatted as a string, effectively containing ceiling values and price tuples. The subtotal of all the items in the basket is checked against the ceiling value. The first ceiling value which is greater or equal to the subtotal provides is used to determine the shipping price.

Example JSON Structure:

````JSON
{
	"50":4.95,
	"90":2.95,
	"100000":0.0
}
````


offers
: A JSON array, formatted as string, describing any offers currently available. Currently, only `discountPerMultiple` offers are available. For each line item in a basket, the code is checked against the offers array: if a match is found, then the offer is applied for that line item.

````JSON
[  
  // 'Buy one get one half price'  
  {"code":"R01",
    "ruleType":"discountPerMultiple",
    "multiple":2,
    "discount":0.25},
  // 'Buy one get one free'  
  {"code":"B01",
    "ruleType":"discountPerMultiple",
    "multiple":2,
    "discount":0.5},
  // 'Buy 3 for 2'
  {"code":"G01",
    "ruleType":"discountPerMultiple",
    "multiple":3,
    "discount":0.3333}
]
````	

In the case of a `discountPerMultiple` rule, the line item quantity is split into in-offer and out-of-offer values, and the discount is only applied to the in-offer component. For example, for 3 x R01 items:
	
````
	2 x R01 -> 2 x (1 - discount ) x price +
	1 x R01 -> 1 x price
````


## Methods

`basket::__toString()`
Provides an overview of the entire basket as a string.

`total()`
Provides a float value of the total of the basket including shipping.

`add($code)` 
Adds 1 item of a given code to the basket.

`addMultiple($code,$qty)`
Adds `$qty` items of a given code to the basket.

`remove($code)`
Removes 1 item of a given code from the basket.

`removeLineitem($code)` 
Removes all items of a given code from the basket.

## Implementation Notes

This is clearly a toy solution. In practice the catalogue, rules, and shipping would be implemented using a backend PDO accessed database.