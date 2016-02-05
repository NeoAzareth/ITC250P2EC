<?php
/**
 * functions.php currently hold the validation of the form
 * 
 * @author Israel Santiago
 * @version 1.0 2016/02/02
 * @link neoazareth.com/ITC250/P2EC/index.php
 */

/**
 * validates the order form and calls the displayAddonsForm method
 
 * @param $post array $_POST
 * @param $items array of item objects
 * @param $toppings array of topping objects
 * @param $order Order class object
 */
function validateForm($post, $items, $toppings, $order)
{
    /*validation varibles are used and modified by the the data on the $post array 
     * in order to be used by the conditions below and validate input*/
    $validInts = sizeof($post);
    $numItems = 0;
    $stringFound = false;
    $negativeInt = false;
    $quantityLessThanTen = true;
    
    //string to be use for error handleling 
    $error = ' ';
    
    /*loops through the $post array to assign the right value 
    *to the validation variables*/ 
    foreach ($post as $item => $value) {
        //condition that ensures validation of only input boxes
        if ($items["$item"]) {
            $numItems += $value;
            if ($value == "") {
                $validInts--;
            }
            if (validateInt($value) === false && $value !== '' && $value !== ' ') {
                $stringFound = true;
            }
            if ($value < 0) {
                $negativeInt = true;
            }
            if ($value > 10) {
                $quantityLessThanTen = false;
            }
        }
    }
    
    /*use the above defined variables to validate "all"(or so I like to think) 
    possible scenarios of the user's input*/
    if ($stringFound === true) {
        $error = '
        Invalid quantity found, 
        please enter positive whole numbers.';
        $order->displayOrderForm($error,$items);
    } elseif ($negativeInt === true) {
        $error = 'Enter positive whole numbers only.';
        $order->displayOrderForm($error,$items);
    } elseif ($validInts === 0 || $numItems === 0) {
        $error = 'Please enter a quantity for the items you want.';
        $order->displayOrderForm($error,$items);
    } elseif ($quantityLessThanTen ===false) {
        $error = 'I am sorry, you can only order 10 or less of each item.
        Contact our catering deparment at ITC250 P2 Team 3';
        $order->displayOrderForm($error,$items);
    } elseif ($validInts > 0 && $numItems > 0) {
        $order->saveOrder($post);
        $order->displayAddOnsForm($items,$toppings);
    } else {
        $error = 'Unknow error; please try again...';
        $order->displayOrderForm($error,$items);
    }
}

/**
 * validates input to be an integer 
 * allows 0 as possible integer value
 * @param $int integer to be validated
 * @return boolean
 */
function validateInt($int)
{
    if (filter_var($int, FILTER_VALIDATE_INT) === false && $int != 0) {
        return false;
    } else {
        return true;
    }
}