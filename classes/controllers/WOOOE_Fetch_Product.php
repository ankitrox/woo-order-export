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
            $this->properties = apply_filters('woooe_product_properties', array('product_name', 'product_categories'));
            $this->set_value();
        }


        /*
         * Gets product names for an order.
         */
        function product_name(){

            $product_names = array();
            
            $line_items = $this->items;

            foreach($line_items as $item){
                array_push($product_names, $item->get_name());
            }

            return $product_names;
        }

        /*
         * Get product categories
         */
        function product_categories(){

            $categories = array();
            $line_items = $this->items;

            foreach($line_items as $item){

                $product = $item->get_product();
                $product_cats = wp_get_object_terms( $product->get_id(), 'product_cat', array('fields'=>'names') );

                if(!empty($product_cats) && !is_wp_error($product_cats)){
                    $categories = implode(', ', $product_cats);
                }
            }

            return $categories;
        }
    }
}