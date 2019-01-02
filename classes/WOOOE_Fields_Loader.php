<?php
//Prevent direct access.
if( !defined('ABSPATH') ){
    exit;
}

if(!class_exists('WOOOE_Fields_Loader')){

    class WOOOE_Fields_Loader {

        /*
         * Fields to export
         */
        public $fields;

        /*
         * values of corresponding fields.
         */
        public $data = array();
        
        /*
         * Static tracker
         */
        static $track;

        /*
         * Constructor. Accepts fields to export.
         */
        function __construct($fields) {

            if(!is_array($fields)){
                $fields = array();
            }

            $this->fields = $fields;
            $this->load_classes();
            $this->write_data();
        }

        /*
         * Loads data into classes and instantiate them
         */
        function load_classes(){

            $current_query = WOOOE_Data_Handler::get_current_query();

            if( $current_query->have_posts() ){

                while( $current_query->have_posts() ){

                    $current_query->the_post();
                    
                    /*
                     * If your fields are using classes.
                     * Load the classes by instantiating them,
                     * classes must use singleton pattern and must use trait WOOOE_Trait_GetValue.
                     */
                    do_action('load_woooe_classes', get_the_ID());
                    
                    self::$track = get_the_ID();
                    $this->data[get_the_ID()] = $this->load_data();
                }
                wp_reset_postdata();
                //Reset tracker
                self::$track = null;
            }
        }
        
        /*
         * Loads data for each order
         */
        function load_data(){
            
            if( self::$track ){
                
                $row = array();
                
                foreach($this->fields as $field){
                    
                    //Check if it has class
                    if( isset($field['class']) ){
                        
                        $field_name = str_replace('woooe_field_', '', $field['id']);
                        $class = $field['class'];
                        $instance = $class::instance(self::$track);
                        $row[$field['id']] = $instance->$field_name;
                    }
                    
                    //Check if field has function
                    if( isset($field['function']) && is_callable($field['function']) ){
                        $row[$field['id']] = $field['function']();
                    }
                }
                
                return $row;
            }
        }

        /*
         * Send the request to write data in file.
         */
        function write_data(){

            if( !is_array($this->data) ){
                return;
            }

            foreach( $this->data as $data ){

                $mapped_data = array_map(function($element){

                    if(is_array($element)){
                        return implode(' | ', $element);
                    }

                    return $element;
                }, $data);

                WOOOE_File_Handler::add_row($mapped_data);
            }
        }
    }
}
