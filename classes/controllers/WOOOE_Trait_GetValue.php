<?php
if(!defined('ABSPATH')){
    exit;
}

/*
 * Define trait to get properties.
 * Since this would be repetitive in case of class fields.
 */
if( !trait_exists('WOOOE_Trait_GetValue', false) ){

    trait WOOOE_Trait_GetValue {

        //Creates instance and stores in property.
        function instance($order_id){
            if( empty(self::$instance[$order_id]) ){
                self::$instance[$order_id] = new self($order_id);
            }
        }
        
        function set_value(){
            foreach($this->properties as $property){
                $this->$property = $this->$property();
            }
        }

        function get_value( $order_id, $property ){

            $value = null;

            if(property_exists(self, $property)){
                $value = self::$instance[$order_id]->$property;
            }

            return $value;
        }
    }
}
