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
        private $fields;
        
        /*
         * values of corresponding fields.
         */
        protected $data;

        /*
         * Constructor. Accespts fields to export.
         */
        function __construct($fields) {

            if(!is_array($fields)){
                $fields = array();
            }

            $this->fields = $fields;
        }

        /*
         * Get headings for data
         */
        function load_data(){
            
            foreach($this->fields as $field){
                
            }
        }
    }
}
