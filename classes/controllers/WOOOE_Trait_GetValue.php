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
        static function instance($order_id){
            if( empty(self::$instance[$order_id]) ){
                self::$instance[$order_id] = new self($order_id);
            }
            
            return self::$instance[$order_id];
        }
        
        function set_value(){
            foreach($this->properties as $property){
                $this->$property = $this->$property();
            }
        }

        function get_value($property){

            $value = null;

            if(property_exists(self, $property)){
                $value = self::$instance[$this->order_id]->$property;
            }

            return $value;
        }
    }
}
