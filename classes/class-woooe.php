<?php
//prevent direct access of file.
if( !defined('ABSPATH') ) {
    exit;
}

if( !class_exists('WOOOE') ){

    class WOOOE {

        static $_instance;

        /*
         * Version of plugin
         */
        public $version = '3.0.0';

        /*
         * Plugin settings array
         */
        public $settings = array();

        //constructor
        protected function __construct() {
            spl_autoload_register(array($this, 'autoload'));
            $this->settings['general']      =   include_once trailingslashit(WOOOE_BASE).'classes/admin-settings/general-settings.php';
            $this->settings['advanced']     =   include_once trailingslashit(WOOOE_BASE).'classes/admin-settings/advanced-settings.php';
            $this->hooks();
        }

        //Prevent cloning and unserialization.
        private function __clone() {}
        private function __wakeup() {}
        /*
         * Instantiate the class
         */
        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /*
         * SPL Autoloader Function
         */
        function autoload($class_name){

            //Load trait in advance.
            include trailingslashit(WOOOE_BASE). 'classes/controllers/WOOOE_Trait_GetValue.php';
            
            if( strstr($class_name, 'WOOOE_Fetch') !== FALSE ){
                include trailingslashit(WOOOE_BASE). 'classes/controllers/'. $class_name .'.php';
            }elseif( strstr($class_name, 'WOOOE') !== FALSE ){
                include trailingslashit(WOOOE_BASE). 'classes/'. $class_name .'.php';
            }
        }

        /*
         * Adds all necessary hooks.
         */
        function hooks(){
            add_action('admin_enqueue_scripts', array($this, 'scripts'));
            add_action('woocommerce_get_settings_pages', function(){new WOOOE_Setting_Tab();});
            add_action('wp_ajax_woooe_get_report', array('WOOOE_Report_Handler', 'fetch_report_stats') );
            add_action('wp_ajax_woooe_fetch_report', array('WOOOE_Report_Handler', 'fetch_report') );
            add_action('init', array('WOOOE_File_Handler', 'download'));
        }
        
        /*
         * Add scripts and styles.
         */
        function scripts(){
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script( 'woooe-script', trailingslashit(WOOOE_BASE_URL).'assets/js/woooe.js', array('jquery-ui-datepicker'), false, true );
            wp_enqueue_style('jquery-ui-datepicker');
            
        }

    }
}