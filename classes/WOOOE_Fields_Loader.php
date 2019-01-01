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
        protected $fields;

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
            $this->load_data();
        }

        /*
         * Get headings for data
         */
        function load_data(){

            $current_query = WOOOE_Data_Handler::get_current_query();

            if( $current_query->have_posts() ){

                while( $current_query->have_posts() ){

                    $current_query->the_post();

                    WOOOE_Fetch_Customer::instance(get_the_ID());
                    WOOOE_Fetch_Product::instance(get_the_ID());

                    /*
                     * If your fields are using classes.
                     * Load the classes by instantiating them,
                     * classes must use singleton pattern and must use trait WOOOE_Trait_GetValue.
                     */
                    do_action('load_woooe_classes', get_the_ID());
                }
            }
        }
    }
}
