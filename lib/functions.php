<?php
/*
 * Get all order statuses in an array.
*/

if(!function_exists('woooe_order_statuses')){
    function woooe_order_statuses() {

        //Get all valid statuses
        $statuses = wc_get_order_statuses();
        $fields = array();

        foreach($statuses as $key=>$status){

            $field = array(
                'name' => $status,
                'type' => 'checkbox',
                'id' => 'wooe_order_status_'. $key
            );

            array_push($fields, $field);
        }

        return $fields;

    }
}

/*
 * Add section start to order statuses section.
 */
if(!function_exists('woooe_order_statuses_section_start')){
    function woooe_order_statuses_section_start($fields){

        $status_section_start = array(
                                'name'     => __( 'Select order statuses to export', 'woooe' ),
                                'type'     => 'title',
                                'desc'     => '',
                                'id'       => 'woooe_title'
                            );

        array_unshift($fields, $status_section_start);

        return $fields;
    }
}

/*
 * Add section end to order statuses section.
 */
if(!function_exists('woooe_order_statuses_section_end')){
    function woooe_order_statuses_section_end($fields){

        $status_section_end = array(
                            'type' => 'sectionend',
                            'id' => 'woooe_title'
                        );

        array_push($fields, $status_section_end);

        return $fields;
    }
}

/*
 * Add settings page link
 */
if(!function_exists('woooe_action_link')){
    function woooe_action_link($links){
        $settings_url = add_query_arg(array('page'=>'wc-settings', 'tab'=>'woooe'), admin_url('admin.php'));
        $settings_link = array(sprintf('<a href="%s">'.__('Settings', 'woooe').'</a>', $settings_url));
        return array_merge($settings_link, $links );
    }

}

/*
 * Re-buld reordering
 */
if(!function_exists('woooe_rebuild_reordering')){

    function woooe_rebuild_reordering(){

        $update = false;

        //Get reorder fields
        $reorder_settings = get_option('woooe_reorder_rename', array());

        //Get exportable fields
        $fields_to_export = WOOOE_Data_Handler::fields_to_export(true);
        $total_fields = wp_list_pluck($fields_to_export, 'name', 'id');

        foreach($reorder_settings as $key=>$val){

            if(!array_key_exists($key, $total_fields)){
                unset($reorder_settings[$key]);
                $update = true;
            }
        }

        if($update){
            $update = update_option('woooe_reorder_rename', $reorder_settings, 'no');
        }
    }
    add_action('woooe_rebuild_reordering', 'woooe_rebuild_reordering');
}

/*
 * Format prices
 */
if(!function_exists('woooe_format_price')){
    
    function woooe_format_price($price = '', $currency = ''){
        
        $price = wc_price($price, array('currency'=>$currency));
        
        //Strip tags from returned value
        $price = strip_tags($price);
        
        //Decode html entities so as to view currency symbols properly
        $price = html_entity_decode($price);
        
        return $price;
    }
}

/*
 * Show add-on purchase notice.
 */
if(!function_exists('woooe_addon_notice')){

    function woooe_addon_notice(){
        global $woooe_addon;
        /*
         * Show this notice if new version (3.4) of add-on plugin
         * is not installed and older version is not activated.
         */
        if((!is_object($woooe_addon) || !is_a($woooe_addon, 'OE_ADDON')) && !is_plugin_active('woocommerce-simply-order-export-add-on/main.php')){
            require trailingslashit(WOOOE_BASE). 'views/woooe-addon.php';
        }
    }
    add_action('admin_notices', 'woooe_addon_notice');
}

/*
 * Show notice for old add-on plugins.
 * Ask to update to the newer version of add-on
 */
if(!function_exists('woooe_update_addon')){

    function woooe_update_addon(){

        /*
         * Show this notice only if older version is activated.
         */
        if(is_plugin_active('woocommerce-simply-order-export-add-on/main.php')){
            require trailingslashit(WOOOE_BASE). 'views/html-notice-addon-update.php';
        }
    }
    add_action('admin_notices', 'woooe_update_addon');
}