<?php
if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('WOOOE_Fetch_Order')){
    
    class WOOOE_Fetch_Order {
        
        public $properties;
        
        function __construct() {
            
            $this->properties = apply_filters('woooe_order_properties', array('order_id','order_status'));
        }
        
        /*
         * Set defined and selected properties.
         */
        function __set($key, $value) {
            //Set only valid properties
            if( in_array($key, $this->properties) ){
                $this->properties[$key] = $value;
            }
        }
        
        /*
         * Get valid properties.
         */
        function __get($name) {
            if(in_array($name, $this->properties)){
               return $this->properties[$name]; 
            }
        }
    }
}