<?php
//Prevent direct access.
if(!defined('ABSPATH')){
    exit;
}

if( !class_exists('WOOOE_Report_Handler', false) ){

    /*
     * Report Handling Class
     */
    class WOOOE_Report_Handler {

        /*
         * Fetches report data
         */
        static function fetch_report(){

            try{

                if(WOOOE_Data_Handler::validate()){
                    
                    WOOOE_Data_Handler::export_data();
                    
                    if(wp_doing_ajax()){
                        self::return_report_status();
                    }elseif(wp_doing_cron()){
                        return array('success'=>true, 'data'=>self::return_report_args());
                    }

                }

            }catch(Exception $e){

                if(is_wp_error($e)){
                    $msg = $e->get_error_message();
                }else{
                    $msg = $e->getMessage();
                }

                if(wp_doing_ajax()){
                    wp_send_json_error($msg);
                }
                elseif(wp_doing_cron()){
                    return array('success'=>false, 'data'=>$msg);
                }
            }
        }

        /*
         * Fetch report stats
         */
        static function fetch_report_stats(){

            if(WOOOE_Data_Handler::validate()){

                try{

                    if(wp_doing_ajax()){
                        self::return_report_status();
                    }elseif(wp_doing_cron()){
                        return array('success'=>true, 'data'=>self::return_report_args());
                    }

                }catch(WP_Error $e){

                    if(is_wp_error($e)){
                        $msg = $e->get_error_message();
                    }else{
                        $msg = $e->getMessage();
                    }

                    if(wp_doing_ajax()){
                        wp_send_json_error($msg);
                    }
                    elseif(wp_doing_cron()){
                        return array('success'=>false, 'data'=>$msg);
                    }
                }
            }
        }
        
        /*
         * Prepare return arguments related to report.
         */
        static function return_report_args(){

            $query = WOOOE_Data_Handler::get_current_query();

            if( ! ($query instanceof WP_Query) ){
                throw new WP_Error( 'invalid_return_query', __('Something went wrong!', 'woooe') );
            }

            $args = array(
                'action' => 'woooe_fetch_report',
                'chunk_size' => WOOOE_Data_Handler::get_chunk_size(),
                'timestamp' => WOOOE_Data_Handler::get_request_params('timestamp'),
                'startDate'=> WOOOE_Data_Handler::get_request_params('startDate'),
                'endDate'=> WOOOE_Data_Handler::get_request_params('endDate'),
                'total_records' => $query->found_posts,
                'offset' => WOOOE_Data_Handler::get_request_params('offset'),
                'fileurl' => add_query_arg(array( 'woooe_download'=> wp_create_nonce('woooe_download'), 'filename'=>WOOOE_Data_Handler::get_request_params('timestamp')), admin_url())
            );
            
            return $args;
        }

        /*
         * Returns the data related to report.
         * Returns total_records, remaining_records etc.
         */
        static function return_report_status(){
            wp_send_json_success(self::return_report_args());
        }
    }
}

