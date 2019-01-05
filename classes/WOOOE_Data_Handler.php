<?php
if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('WOOOE_Data_Handler')){

    class WOOOE_Data_Handler {

        /*
         * Chunk size - number of records to fetch at single run.
         */
        static $chunk_size =3;

        /*
         * Holds the current query
         */
        static $query;

        /*
         * Starts the export process.
         * Creates file, loads data, writes file etc.
         */
        static function export_data(){

            $file_handler = WOOOE_File_Handler::prepare_file();
            $field_loader = new WOOOE_Fields_Loader(self::fields_to_export());
        }

        /*
         * Get chunk size
         */
        static function get_chunk_size(){
            return apply_filters('woooe_chunk_size', self::$chunk_size);
        }
        
        /*
         * Gets the current instance of query.
         */
        static function get_current_query(){

            if(!(self::$query instanceof WP_Query)){
                $args = self::get_report_args();
                self::$query = new WP_Query($args);
            }
            
            return self::$query;
        }

        /*
         * Returns the fields which needs to be exported.
         */
        static function fields_to_export(){

            global $woooe;

            $fields = array();

            foreach( $woooe->settings['general'] as $value ){

                if( isset($value['export_field']) && 'yes' == $value['export_field'] && 'yes' == woocommerce_settings_get_option($value['id'], 'no') ){
                    array_push($fields, $value);
                }
            }

            return $fields;
        }

        /*
         * Returns the order statuses to export.
         */
        static function get_statuses(){
            
            global $woooe;
            $statuses = array_keys(wc_get_order_statuses());
            
            $selected_statuses = array();
            
            foreach($statuses as $staus){
                
                if( 'yes' == woocommerce_settings_get_option('wooe_order_status_'.$staus, 'no') ){
                    array_push($selected_statuses, $staus);
                }
            }
            
            return !empty($selected_statuses) ? $selected_statuses : $statuses;
        }

        /*
         * Get arguments related to getting reports for orders.
         */
        static function get_report_args(){

            /*
             * Arguments for fetching.
             */

            $args = array(
                        'post_type'=>'shop_order',
                        'posts_per_page'=> self::get_chunk_size(),
                        'post_status'=> self::get_statuses(),
                        'offset'=> (self::get_request_params('offset') * self::get_chunk_size())
                    );

            //Date query for orders.
            $args['date_query'] = array(
                                    'after' => self::get_request_params('startDate'),
                                    'before' => self::get_request_params('endDate'),
                                    'inclusive' => true,
                                );
            
            return apply_filters('woooe_report_args', $args);
        }

        /*
         * Get request parameters
         */
        static function get_request_params($return = null){

            /*
             * Requested return value should be scalar
             */
            if(!is_scalar($return)){
                return array();
            }
            
            $startDate  = filter_input(INPUT_POST, 'startDate');
            $endDate    = filter_input(INPUT_POST, 'endDate');
            $offset     = filter_input(INPUT_POST, 'offset');
            $total_records  = filter_input(INPUT_POST, 'total_records');
            $chunk_size = filter_input(INPUT_POST, 'chunk_size');
            $timestamp  = filter_input(INPUT_POST, 'timestamp');
            //Default offset is 1
            $offset = !empty($offset) ? $offset : 0;
            $timestamp = !empty($timestamp) ? $timestamp : time();

            $return_data = compact('startDate', 'endDate', 'offset', 'chunk_size', 'total_records', 'timestamp');

            if(!empty($return) && isset($return_data[$return])){
                return $return_data[$return];
            }
            
            return $return_data;
        }

        /*
         * Validates report data
         */
        static function validate(){

            $startDate  = self::get_request_params('startDate');
            $endDate    = self::get_request_params('endDate');

            if( empty($startDate) || empty($endDate) ){
                throw new WP_Error( 'empty_period', __('Enter start date and End date', 'woooe') );
            }

            return true;
        }


    }
}