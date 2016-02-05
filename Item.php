<?php
/**
 * Item.php describes the class Item and all of it's methods
 * 
 * @author Israel Santiago
 * @version 1.0 2016/02/02
 * @link neoazareth.com/ITC250/P2EC/index.com
 */

/**
 * class created item objects, with properties: name, description, price and addons
 * associated with the item itself.
 * 
 * methods display properties ready for table instertion, formatted price as currency
 * calculate item subtotal, get the item's addons ready for table format
 * and get the item subtotal ready for table format
 * 
 * <code>
 * $icecream = new Item('Ice Cream', 'Three flavors', 3.50)
 * $burger = new Item('Hamburger', 'Includes cheese', 7.99,addons = ['lettuce','tomatos','fries'])
 * </code>
 * 
 * @see Topping class; class that creates topping objects
 * @see Order class; class that creates Order objects
 */
class Item
{
    //declare class variables/properties
    public $name = '';
    public $description = '';
    public $price = 0.0;
    public $addOns = [];
    
    /**
     * constructor
     *
     * php multiple constructor function
     */
    public function __construct()
    {
    	//gets the arguments passed an stores them in the $a variable
        $a = func_get_args();
        
        //counts the arguments passed an stores the number in the $i variable
        //very important to name the other constructor with the number of
        //arguments they take
        $i = func_num_args();
       	
       	//calls the appropiate constructor based on the number of arguments
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    /**
     * constructor with 3 arguments
     * 
     * @param $name string; the name of the item
     * @param $description string; the description of the item
     * @param $price double; the price of the item
     * @return object
     */
    public function __construct3($name, $description, $price)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }
    
    /**
     * constructor with 4 arguments
     * 
     * @param $name string; the name of the item
     * @param $description string; the description of the item
     * @param $price double; the price of the item
     * @param $addOns array; holds keys to be used as reference for the toppings array
     * @return object
     */
    public function __construct4($name, $description, $price, $addOns)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->addOns = $addOns;
    }
    
    /**
     * formats the objects price
     * @return formatted price e.g. $00.00
     */
    public function getFormattedPrice()
    {
        $price = '$' . number_format($this->price,2);
        return $price;
    } 
        
    /**
     * creates a string with an input box, and the item properties ready for table 
     * insertion.
     *
     * @param $name string used to be inserted as the input box name
     * @return string
     */
    public function toString($name)
    {
        $string = '<tr><td><input type="text" name="' . $name . '" /></td> 
                        <td>' . $this->name . '</td> 
                        <td> '. $this->description .'</td> 
                        <td>'. $this->getFormattedPrice() .'</td></tr>';
        return $string;
    }
    
    /**
     * gets the item addons, if any, ready for table insertion
     *
     * @param $name string to be used as the name of the checkbox
     * @param $toppings array of topping objects, used as reference
     * @return string
     */
    public function getAddOns($name,$toppings)
    {
        $addOns = '';
        if ($this->addOns) {
            $addOns .= '<tr><td></td>';
            foreach ($this->addOns as $value) {
                $addOns .= $toppings["$value"]->toString($name,$value);
            }
            $addOns .= '</tr>';
        }
        return $addOns;
    }
    
    /**
     * creates a string with the item's quantity, name, price and item's subtotal
     *
     * to be used for the final output
     * @param $quantity integer that especifies the item quantity
     * @return string
     */
    public function getItemSubTotalToString($quantity)
    {
        $plural = '';
        if ($quantity >1) {
            $plural = 's';
        }
        $string = '
        <tr>
        <td>' . $quantity .'</td>
        <td>'. $this->name . $plural .'</td>
        <td>'. $this->getFormattedPrice() .'</td>
        <td> $'. number_format($this->getItemSubTotal($quantity),2) . '</td>
        </tr>';
        return $string;
    }
    
    
    /**
     * calculates the item's subtotal
     *
     * @param $quantity integer item quantity
     * @return integer
     */
    public function getItemSubTotal($quantity)
    {
        return $this->price * $quantity;
    }
}