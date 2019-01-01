<?php
if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('WOOOE_Fetch_Customer')){

    class WOOOE_Fetch_Customer extends WOOOE_Fetch_Order{

        use WOOOE_Trait_GetValue;

        /*
         * Customer object
         */
        public $customer;

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
            $this->customer = new WC_Customer($this->order->get_user_id());
            $this->properties = apply_filters('woooe_order_properties', array('customer_name','customer_email') );
            $this->set_value();
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

        /*
         * Get customer name
         */
        function customer_name(){

            $name = '';

            $fname = $this->customer->get_first_name();
            $lname = $this->customer->get_first_name();

            $name = trim($fname.' '.$lname);

            return $name;
        }
        
        /*
         * Get customer email
         */
        function customer_email(){
            return $this->order->get_billing_email();
        }

    }
}