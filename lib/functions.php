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