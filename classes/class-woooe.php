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
        function __construct() {
            spl_autoload_register(array($this, 'autoload'));
            $this->settings['general']      =   include_once trailingslashit(WOOOE_BASE).'classes/admin-settings/general-settings.php';
            $this->settings['advanced']     =   include_once trailingslashit(WOOOE_BASE).'classes/admin-settings/advanced-settings.php';
            $this->hooks();
        }

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

            if( strstr($class_name, 'WOOOE_Fetch') !== FALSE ){
                include trailingslashit(WOOOE_BASE). 'classes/controllers/'. $class_name .'.php';
            }

            if( strstr($class_name, 'WOOOE') !== FALSE ){
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