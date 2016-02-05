<?php
/**
 * index.php holds the instatiation of all the objects used by the program.
 * on load or post, the application executes a command based on the
 * information on the $_POST superglobal.
 * The overall purpose of the program is to display a form to enter item
 * quantities, validate that input, display another form with addons
 * based on the quantities and finally display the results as series of
 * human friendly sentences and a breakdown of the order's total price
 * 
 * @author Israel Santiago
 * @version 2.0 2016/02/02
 * @link neoazareth.com/ITC250/P2EC/index.php
 */

//define use of this page to process post action
define('THIS_PAGE',basename($_SERVER['PHP_SELF']));

//include statements 
include 'Item.php';
include 'functions.php';
include 'Topping.php';
include 'Order.php';

//object instantiation
$newOrder = new Order();

$items["burrito"] = new Item("Burrito", "Includes awesome sauce!",7.95, $addOns = ["chips","rice","jalapeno"]);
$items["taco"] = new Item("Taco", "Includes cheese and lettuce!",3.99,$addOns = ["tomato","cream","beans"]);
$items["icecream"] = new Item("Fried icecream", "Includes free sprinkles!",5.00);
$items["milkshake"] = new Item("Milkshake", "Three different flavors", 6.00);

$toppings["tomato"] = new Topping("tomatos", .50); 
$toppings["cream"] = new Topping("sour cream", .99);
$toppings["jalapeno"] = new Topping("jalapeno", .50);
$toppings["chips"] = new Topping("potato chips", 3.50);
$toppings["rice"] = new Topping("mexican rice", .50);
$toppings["beans"] = new Topping("refried beans", .50);
$toppings["syrup"] = new Topping("chocolate syrup", .50);
$toppings["double-icecream"] = new Topping("double icecream", .50);

/*check for values stored on $_POST and runs the appropiate command*/
if (isset($_POST['next'])) {
    validateForm($_POST,$items,$toppings,$newOrder);
} elseif (isset($_POST['place-order'])) {
    $newOrder->orderSummary($_POST, $items,$toppings);
} elseif (isset($_POST['reset'])) {
    $newOrder->displayOrderForm(' ',$items,$toppings);
} else {
    $newOrder->displayOrderForm(' ',$items,$toppings);
}