<?php
$order_statuses_fields = woooe_order_statuses_section_end(woooe_order_statuses_section_start(woooe_order_statuses()));

$export_fashion = array(
    
        array(
            'name'     => __( 'Export style', 'woooe' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'woooe_export_style'
        ),
    
        array(
            'type'     => 'radio',
            'options'  => array(
                'inline' => __('Export each order on single row', 'woooe'),
                'separate' => __('Export each product in order in separate row', 'woooe')
            ),
            'id'       => 'woooe_field_start_date'
        ),


        array(
             'type' => 'sectionend',
             'id' => 'woooe_export_style'
        ),

);

$fields = array_merge($order_statuses_fields, $export_fashion);

return apply_filters( 'woooe_settings_fields_advanced', $fields );