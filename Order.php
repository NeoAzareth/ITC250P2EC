<?php
/**
 * Order.php describes the class Order and all of it's methods
 *
 * @author Israel Santiago
 * @version 1.0 2016/02/02
 * @link neoazareth.com/ITC250/P2EC/index.com
 * @todo orderSummary fucntion needs refactoring
 */

/**
 * class that creates Order objects
 *
 * methods that display item form, toppings form, 
 * process the order and create
 * a summary of the order
 *
 * <code>
 * $myOrder = new Order();
 * </code>
 *
 * @see Topping class; class that creates topping objects
 * @see Item class; class that creates Item objects
 */

class Order
{
    //class varibles/properties
    public $subTotal = 0.0;
    public $orderDetails = [];
    const TAX = 9;
    public $numberWords 
    = array('One ','Two ','Three ','Four ','Five ','Six ','Seven ','Eight ','Nine ','Ten ',);
    public $ordinalNums 
    = array('1st ','2nd ','3rd ','4th ','5th ','6th ','7th ','8th ','9th ','10th ');
    
    /**
     * constructor takes no arguments
     *
     * @return empty order object
     */
    public function __construct(){}
    
    
   	/**
     * displays the order form
     * 
     * creates a table with all available items stored in the $items array
     * 
     * @param $error string used to display the form with different error messages
     * @param $items array that holds item objects
     */
    public function displayOrderForm($error, $items)
    {
        echo '
        <form method="post" action=" ' . THIS_PAGE . ' ">
        <table>
        <tr>
        <td>Quantity</td><td>Item</td><td>Item Description</td><td>Item price</td>
        </tr>'; 
        //loops through all the items in the array and calls the method toString()
        foreach ($items as $key => $object) {
            echo $object->toString($key);
        } 
        echo '
        </table>
        <input type="submit" name="next" value="Add Extras" />
        <p>'. $error .'</p>
        </form>
        ';   
    }//end displayOrderForm
    
    /**
     * sets and sorts the input of the $_POST superglobal as the object 
     * property orderDetails
     * 
     * @param $post array $_POST
     */
    public function saveOrder($post)
    {
        array_pop($post);
        $sortedOrder = [];
        foreach ($post as $key => $value) {
            $sortedOrder[$key] = $value;
        }
        ksort($sortedOrder);
        $this->orderDetails = $sortedOrder;
    }//end saveOrder
    
    
    /**
     * displays a form to add extras to the items
     * 
     * @param $items array that contains item objects, used as reference
     * @param $toppings array that contains topping objects, used as reference
     */
    public function displayAddOnsForm($items, $toppings)
    {
        echo '
        <h3>Any extras?</h3>
        <form method="post" action=" ' . THIS_PAGE . ' ">
        <table>';
        /*loops through the previously set orderDetails property to display 
         * the ordered items that have add ons*/ 
        foreach ($this->orderDetails as $item => $value) {
            if ($items["$item"]->addOns) {
                echo '
                <input type="hidden" name="'. $item .'" value="'. $value .'" /> ';
                $name = $items["$item"]->name; 
                /*for loop used to display several lines of the same item
                 * when the quantity is greater than 1*/
                for ($x = 0; $x<= $value-1; $x++) {
                    echo '
                    <tr>
                    <td>' . $this->ordinalNums[$x] . $name . '</td>
                    </tr>';
                    
                    //calls the getAddOns method and passes a composite name
                    //made out of the current value of $x plus a dash("-") plus
                    //the item name
                    // e.g 1-burrito
                    echo $items["$item"]->getAddOns($x+1 . '-' . $item,$toppings);
                }
            }
        }
        //display the items with no add ons
        echo '<tr><td>No extras for these...</td></tr> ';
        foreach ($this->orderDetails as $item => $value) {
            if (!($items["$item"]->addOns) && $value != '') {
                if ($value > 1)
                {
                    $items["$item"]->name .= 's';
                }
                echo '
                <input type="hidden" name="'. $item .'" value="'. $value .'" /> 
                <tr>
                <td>' . $value . ' ' . $items["$item"]->name . '</td>
                </tr>';
            }
        }
        echo '</table>
        <input type="submit" name="place-order" value="Place Order?" />
        </form>
        ';
    }//end displayAddonsForm
    
    /**
     * this function creates a neat series of senteces out of the order
     * while joining duplicates.
     *
     * e.g. 
     *instead of:    1 Burrito with lettuce.
     *               1 Burrito with lettuce.   as summary.
     * 
     * you get:       2 Burritos with lettuce.
     *
     * I don't like commenting a lot but since this function is so long
     * and does so much, I decided to explain the logic behind it.
     *
     *@param $post array $_POST data
     *@param $items array of item objects; used as reference
     *@param $toppings array of topping objects; used as reference
     *@todo find a way to simplify or separate this into several methods
     */
    public function orderSummary($post,$items,$toppings)
    {
        /*variable used by the method to store and organize the
        * the order summary*/
        $summaryArray = [];
        
        //string used to hold an item's summary at a time
        $line = '';
        
        //integer used to tell the program how many toppings
        //have been added
        $counter = 0;
        
        //save the $_POST data into the object's orderDetails property
        $this->saveOrder($post);
        
        //loop through everything on the orderDetils property
        foreach ($this->orderDetails as $item => $quantity) {
            //condition that allows only item objects
            if ($items["$item"] && $quantity > 0) {
                //loops to repeat the line x quantity of items
                for ($x = 1; $x<= $quantity; $x++) {
                    
                    /*checks if a key exists in the summary array.
                    *$line refers to an item's summary.
                    *example key: "Burrito with tomatos"*/
                    if (array_key_exists($line,$summaryArray)) {
                        //if the key and $line matches, increases its value by 1
                        $summaryArray["$line"]++;
                    } elseif ($line != '') {
                        //if not adds the key and sets its value to one
                        $summaryArray["$line"] = 1;
                    }
                    //resets the line to start over 
                    $line = '';
                    //adds the items name and a period
                    $line .= $items["$item"]->name . '.';
                    //resets the counter to indicate there are not toppings in the string
                    $counter = 0;
                    
                    //loops through the orderDetails property and checks for toppings
                    //that meet the criteria
                    foreach ($this->orderDetails as $topping => $value) {
                        //condition that ensures the value is a topping
                        if ($toppings["$value"] && $value != '') {
                            
                            /*essential for creating the summary
                            *this BIF explodes the $topping variable 
                            *into an array
                            *e.g  $topping = "1-burrito-lettuce"
                            *after explode $topping = ['1','burrito','lettuce']
                            *the number indicates which number item this topping
                            *belongs to. e.g "2-taco-lettuce"
                            *topping key means the extra lettuce belongs to the 2nd taco*/
                            $topping = explode('-',$topping);
                            
                            //here we check if the topping belongs to the $x number of
                            //$item
                            if ($topping[0]== $x && $item == $topping[1]) {
                                //gets rid of the period
                                $line = substr_replace($line,'',-1);
                                //depending of the value of the counter(number of topping)
                                //these conditions add the 'with', 'and' or replace
                                //the 'and' with a comma and adds an 'and'; 
                                //and adds the current topping
                                if ($counter == 0 ) {
                                    $line .= ' with ' . $toppings["$value"]->name;
                                } elseif ($counter == 1) {
                                    $line .= ' and ' . $toppings["$value"]->name;
                                } else {
                                    $line = str_replace(' and',',',$line);
                                    $line .= ' and ' . $toppings["$value"]->name; 
                                }
                            //increases the counter by 1 to indicate 
                            //the next topping is a different topping
                            $counter++;
                                
                            //adds the period once more
                            $line .= '.';
                            }
                    	}
                    }
                }
            //this condition organizes and saves the addOns
            //into the orderDetails property
            } elseif ($toppings["$quantity"] && $quantity != '') {
                if (array_key_exists($quantity,$this->orderDetails)) {
                    $this->orderDetails["$quantity"]++;
                } else {
                    $this->orderDetails["$quantity"] = 1;
                }
            }
        }
        //on this part we ensure the last $line is added to the summary
        if (array_key_exists($line,$summaryArray)) {
            $summaryArray["$line"]++;
        } elseif ($line != '') {
            $summaryArray["$line"] = 1;
        }
        
        //finally we output the results
        echo "<h2>Here is your order!</h2>";
        foreach ($this->makePlural($summaryArray, $items) as $key => $value) {
            echo '<p>'. $this->numberWords[$value-1] . ' ' . $key . '</p>';
        }
        $this->processOrder($items, $toppings);
    }//end of order summary function
    
    /**
     * takes an array and searches for the value to be greater than 
     * one, then changes the key(name) of the item to plural and saves
     * the updated data in the another array that is returned
     *
     * @param $summaryArray array that is presumed to hold the summary of the order 
     * @param $items array of items; used as reference
     * @return array with items in plural
     */
    public function makePlural($summaryArray, $items)
    {
        $sortedArray = [];
        foreach ($summaryArray as $item => $quantity) {
            foreach ($items as $key => $object) {
                $name = $object->name;
                $int = strpos($item,$name);
                if ($int >= 0 && $int !== false && $quantity > 1) {
                    $string = str_replace($name, $name . 's',$item);
                    $sortedArray["$string"] = $quantity;
                } elseif ($int >= 0 && $int !== false) {
                    $sortedArray["$item"] = $quantity;
                }
            }
        }
        return $sortedArray;
    }
    
    /**
     * displays a table with the items' and topppings' quantities, prices and
     * subtotals; and the order's subtotal, tax and total
     *
     * @param $items array of item objects; used as reference
     * @param $toppings array of toppings; used as reference
     */
    public function processOrder($items ,$toppings)
    {
        echo '
       	<table border="1" >
        <tr>
        <td>Quantity</td><td>Item</td><td>Item price</td><td>Item Subtotal</td>
        </tr>';
        
        /*loops through the $post array and uses the $item to reference 
         * the $items array and call the getItemSubTotalToString() method*/
        foreach ($this->orderDetails as $item => $quantity) {
            if ($items["$item"] && $quantity >0) {
                echo $items["$item"]->getItemSubTotalToString($quantity);
                //adds the item subtotal to the order subtotal
                $this->subTotal += $items["$item"]->getItemSubTotal($quantity);
            } elseif ($toppings["$item"] && $quantity > 0) {
                echo $toppings["$item"]->getToppingSubTotalToString($quantity);
                $this->subTotal += $toppings["$item"]->getToppingSubTotal($quantity);
            }
        }
        $taxTotal = number_format($this->subTotal * (self::TAX/100),2);
        $total = number_format($this->subTotal + $taxTotal,2);

        echo '
        <tr></tr>
        <tr>
        <td></td>
        <td></td>
        <td>Order Subtotal</td><td>$'. number_format($this->subTotal,2) .'</td></tr>
        <tr>
        <td></td>
        <td></td>
        <td>Tax</td><td>$'. $taxTotal .'</td></tr>
        <tr>
        <td></td>
        <td></td>
        <td>Total</td><td>$'. $total .'</td></tr>
        </table>

        <form method="post" action="'. THIS_PAGE .'" >
        <input type="submit" name="reset" value="Place another order?" />
        </form>
        ';
    }//end of processOrder

}//end of class

