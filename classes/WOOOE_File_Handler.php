<?php
if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('WOOOE_File_Handler', false)){

    class WOOOE_File_Handler{

        /*
         * Directory name where exported files will be stored.
         */
        static $dir = 'woo-order-export';

        /*
         * Extension for file.
         */
        static $extension = '.csv';

        /*
         * File handler
         */
        static $file;

        /*
         * Creates a folder inside uploads to store .csv files.
         */
        static function create_folder(){

            $upload_directory = wp_upload_dir();

            /*
             * If directory is already present, then do not attempt to create.
             */
            if(file_exists(trailingslashit($upload_directory['basedir']). self::$dir.'/')){
                return;
            }

            if(self::upload_dir()){
                if( !mkdir( trailingslashit($upload_directory['basedir']). self::$dir.'/' ) ){
                    throw new Exception( __('Cannot create directory inside uploads folder.', 'woooe') );
                }
            }else{
                throw new Exception( __('Cannot create directory inside uploads folder.', 'woooe') );
            }
        }

        /*
         * Get upload directory for order export plugin
         */
        static function upload_dir(){

            $upload_directory = wp_upload_dir();
            
            if( empty($upload_directory['error']) && isset($upload_directory['basedir']) && is_writable($upload_directory['basedir']) ){
                return trailingslashit($upload_directory['basedir']). self::$dir.'/';
            }
            
            return false;
        }

        /*
         * Checks if a file exists, if not, creates it.
         * If it is present, opens the file for writing in append mode.
         */
        static function prepare_file(){

            if(is_null(self::$file)){

                //temporary name for file which is timestamp
                $temp_filename  =   WOOOE_Data_Handler::get_request_params('timestamp').self::$extension;
                $filepath       =   self::upload_dir() . $temp_filename;

                $mode = file_exists($filepath) ? 'a' : 'w';
                $fields = wp_list_pluck(WOOOE_Data_Handler::fields_to_export(), 'name');

                if( !$file_pointer = fopen($filepath, $mode) ){
                    throw new Exception( __('Couldn\'t create the file.', 'woooe') );
                }

                //If newly created file, then write headings to it.
                $length = ('w' === $mode) ? fputcsv($file_pointer, $fields) : '';
                self::$file = $file_pointer;
            }

            return self::$file;
        }

        /*
         * Appends the data to the file.
         */
        static function add_row($data){

            if( count($data) === count(WOOOE_Data_Handler::fields_to_export()) ){
                $string_length = fputcsv(self::prepare_file(), $data);
            }
        }
        
        /*
         * Get valid download filename
         */
        static function filename(){
            if ( ! class_exists( 'WC_Admin_Settings', false ) ) {
                include( dirname( WC_PLUGIN_FILE ) . '/includes/admin/class-wc-admin-settings.php' );
            }

            $filename   =   woocommerce_settings_get_option('woooe_field_export_filename', 'orderexport.csv');
            $filename   =   !empty($filename) ? $filename : 'orderexport';
            $pathinfo   =   pathinfo($filename);
            
            //If extension is set
            if(!empty($pathinfo['extension']) && 'csv' === $pathinfo['extension']){
                return $filename;
            }
            
            return $pathinfo['filename'].'.csv';
        }
        
        /*
         * Download the csv file.
         */
        static function download(){

            $wooe_download = filter_input(INPUT_GET, 'woooe_download', FILTER_DEFAULT);
            $wooe_filename = filter_input(INPUT_GET, 'filename', FILTER_DEFAULT);

            if( !empty($wooe_filename) && !empty($wooe_download) && file_exists(path_join( self::upload_dir(), $wooe_filename.'.csv'))
                && wp_verify_nonce($wooe_download, 'woooe_download')
            ){
                $charset    =   get_option('blog_charset');
                $csv_file   =   path_join( self::upload_dir(), $wooe_filename.'.csv');

                header('Content-Description: File Transfer');
                header('Content-Type: application/csv');
                header("Content-Disposition: attachment; filename=". self::filename());
                header("Expires: 0");
                header('Cache-Control: must-revalidate');
                header('Content-Encoding: '. $charset);
                header('Pragma: public');
                header('Content-Length: ' . filesize($csv_file));
                readfile($csv_file);
                unlink($csv_file);
                exit;
            }
        }
    }
}