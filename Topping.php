<?php
/**
 * Topping.php describes the class Topping and all of it's methods
 * 
 * @author Israel Santiago
 * @version 1.0 2016/02/02
 * @link neoazareth.com/ITC250/P2EC/index.com
 */

/**
 * class creates topping objects, with properties: name and price.
 * 
 * methods display properties ready for table instertion, formatted price as currency
 * calculate topping subtotal and get the topping subtotal ready for table format
 * 
 * <code>
 * $lettuce = new Topping('lettuce', 0.50)
 * </code>
 * 
 * @see Item class; class that creates Item objects
 * @see Order class; class that creates Order objects
 */

class Topping
{
    //class variables/properties
    public $name = ' ';
    public $price = 0.0;

    /**
     * constructor
     * 
     * @param $name string; the name of the topping
     * @param $price double; the price of the topping
     * @return topping object 
     */
    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
    
    /**
     * gets the object's formatted price
     *
     * @return string e.g. $00.00
     */
    public function getFormattedPrice()
    {
        $price = '$'.number_format($this->price,2);
        return $price;
    }
    
    /**
     * creates a string with a checkbox, name and price properties
     *
     * @param $key string to be used as the checkbox name
     * @param $value string to be used as the checkbox name
     * the $key and $value string are concatinated with a dash('-')
     * in order to create a unique name
     */
    public function toString($key,$value)
    {
        $string = '
        <td>
        <input type="checkbox" name="'. $key .'-'. $value .'" value="'. $value .'" > 
        add '. $this->name .' 
        '. $this->getFormattedPrice() .'</td>
        ';
        return $string;
    }
    
    /**
     * creates a string with the topping's properties and topping
     * subtotal ready for table insertion
     *
     * @param $quantity integer topping quantity
     * @return string
     */
    public function getToppingSubTotalToString($quantity)
    {
        $string = '
        <tr>
        <td>' . $quantity .'</td>
        <td> extra '. $this->name .'</td>
        <td> '. $this->getFormattedPrice() .'</td>
        <td> $'. number_format($this->getToppingSubTotal($quantity),2) . '</td>
        </tr>';
        return $string;
    }
    
    /**
     * calculates the topping subtotal
     *
     * @param $quantity integer; topping quantity
     * @return integer
     */
    public function getToppingSubTotal($quantity)
    {
        return $this->price * $quantity;
    }
}