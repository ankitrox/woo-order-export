<?php
if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('WOOOE_Fetch_Order', false)){

    class WOOOE_Fetch_Order {

        use WOOOE_Trait_GetValue;

        /*
         * Order ID
         */
        public $order_id, $order, $order_number;
        
        static $instance = array();

        //Constructor
        function __construct($order_id) {

            $this->order_id = $order_id;
            $this->order = wc_get_order($order_id);
            $this->order_number = $this->order->get_order_number();
            $this->order_status = $this->order->get_status();
            $this->order_date = $this->order->get_date_created();
            $this->items = $this->order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
        }
    }
}