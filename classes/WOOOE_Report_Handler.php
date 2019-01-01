<?php

//Prevent direct access.
if(!defined('ABSPATH')){
    exit;
}

if( !class_exists('WOOOE_Report_Handler') ){

    /*
     * Report Handling Class
     */
    class WOOOE_Report_Handler {

        /*
         * Fetches report data
         */
        static function fetch_report(){

            $response = array();

            try{

                if(WOOOE_Data_Handler::validate()){
                    WOOOE_Data_Handler::export_data();
                    self::return_report_status();
                }

            }catch(Exception $e){

                $response['error'] = true;
                
                if(is_wp_error($e)){
                    $msgs = $e->get_error_messages();
                    foreach( $msgs as $msg ){
                        $response['error_msgs'] = '<p>'.$msg.'</p>';
                    }
                }
            }
        }

        /*
         * Fetch report stats
         */
        static function fetch_report_stats(){

            if(WOOOE_Data_Handler::validate()){
                try{
                    self::return_report_status();
                }catch(WP_Error $e){
                    wp_send_json_error($e->get_error_message());
                }
            }
        }

        /*
         * Returns the data related to report.
         * Returns total_records, remaining_records etc.
         */
        static function return_report_status(){

            $query = WOOOE_Data_Handler::get_current_query();

            if( ! ($query instanceof WP_Query) ){
                throw new WP_Error( 'invalid_return_query', __('Something went wrong!', 'woooe') );
            }

            $args = array(
                'action' => 'woooe_fetch_report',
                'chunk_size' => WOOOE_Data_Handler::get_chunk_size(),
                'timestamp' => time(),
                'startDate'=> WOOOE_Data_Handler::get_request_params('startDate'),
                'endDate'=> WOOOE_Data_Handler::get_request_params('endDate'),
                'total_records' => $query->found_posts,
                'offset' => ( WOOOE_Data_Handler::get_chunk_size() * WOOOE_Data_Handler::get_request_params('offset') )
            );

            wp_send_json_success($args);
        }

    }
}

