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
         * 
         */
        static $extension = '.csv';

        static function create_folder(){
            
            $upload_directory = wp_upload_dir();
            
            if(empty($upload_directory['error']) && isset($upload_directory['basedir']) && is_writable($upload_directory['basedir'])){
                if( !mkdir( trailingslashit($upload_directory['basedir']). self::$dir.'/' ) ){
                    throw new Exception( __('Cannot create directory inside uploads folder.', 'woooe') );
                }
            }
        }
        
        static function create_file(){
            
        }
    }
}