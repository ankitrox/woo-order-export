<?php
if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('WOOOE_Fetch_Product', false)){

    class WOOOE_Fetch_Product extends WOOOE_Fetch_Order{

        use WOOOE_Trait_GetValue;

        /*
         * Holds instance of class
         */
        static $instance = array();

        /*
         * Properties array.
         */
        public $properties;


        function __construct($order_id) {

            parent::__construct($order_id);
            $this->properties = apply_filters('woooe_product_properties', array('product_name'));
            $this->set_value();
        }


        /*
         * Gets product names for an order.
         */
        function product_name(){

            $product_names = array();

            $line_items = $this->order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );

            foreach($line_items as $item){
                array_push($product_names, $item->get_name());
            }
            
            return $product_names;
        }
    }
}