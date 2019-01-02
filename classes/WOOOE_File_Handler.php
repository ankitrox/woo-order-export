<?php
if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('WOOOE_File_Handler')){

    class WOOOE_File_Handler {

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

            if( empty($upload_directory['error']) && isset($upload_directory['basedir']) && !file_exists(trailingslashit($upload_directory['basedir']). self::$dir.'/') && is_writable($upload_directory['basedir']) ){
                if( !mkdir( trailingslashit($upload_directory['basedir']). self::$dir.'/' ) ){
                    throw new Exception( __('Cannot create directory inside uploads folder.', 'woooe') );
                }
            }
        }

        /*
         * Checks if a file exists, if not, creates it.
         * If it is present, opens the file for writing in append mode.
         */
        static function prepare_file(){

            if(is_null(self::$file)){

                $upload_directory = wp_upload_dir();
                //temporary name for file which is timestamp
                $temp_filename  =   WOOOE_Data_Handler::get_request_params('timestamp').self::$extension;
                $filepath       =   trailingslashit($upload_directory['basedir']). self::$dir.'/'.$temp_filename;

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
    }
}