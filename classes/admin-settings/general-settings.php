<?php

$filename_section = array(

        array(
            'name'     => __( 'Enter export filename', 'woooe' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'woooe_filename'
        ),

        array(
            'name'     => __( 'File Name', 'woooe' ),
            'type'     => 'text',
            'id'       => 'woooe_field_export_filename'
        ),

        array(
             'type' => 'sectionend',
             'id' => 'woooe_filename'
        ),

);

$fields = apply_filters('woooe_exportable_fields', array(
    
        array(
            'name'     => __( 'Order ID', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_order_id',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Order'
        ),

        array(
            'name'     => __( 'Order Number', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_order_number',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Order'
        ),

        array(
            'name'     => __( 'Order Date', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_order_date',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Order'
        ),
    
        array(
            'name'     => __( 'Customer Name', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_customer_name',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Customer'
        ),

        array(
            'name'     => __( 'Customer Email', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_customer_email',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Customer'
        ),

        array(
            'name'     => __( 'Product Name', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_product_name',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Product'
        ),

        array(
            'name'     => __( 'Product Categories', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_product_categories',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Product'
        ),

        array(
            'name'     => __( 'Order Status', 'woooe' ),
            'type'     => 'checkbox',
            'id'       => 'woooe_field_order_status',
            'export_field' => 'yes',
            'class' => 'WOOOE_Fetch_Order'
        ),

));

$fields_section_start = array(
        array(
            'name'     => __( 'Choose fields to export', 'woooe' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'woooe_title_sm'
        ),

);

$fields_section_end = array(

        array(
             'type' => 'sectionend',
             'id' => 'woooe_title_sm'
        ),

);

$fields_section = array_merge($fields_section_start, $fields, $fields_section_end);

$export_duration = array(

        array(
            'name'     => __( 'Select export duration & Export', 'woooe' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'woooe_export_duration'
        ),

        array(
            'name'     => __( 'Start Date', 'woooe' ),
            'type'     => 'text',
            'class'    => 'woooe-datepicker',
            'custom_attributes' => array('autocomplete'=>'off'),
            'id'       => 'woooe_field_start_date'
        ),

        array(
            'name'     => __( 'End Date', 'woooe' ),
            'type'     => 'text',
            'class'    => 'woooe-datepicker',
            'custom_attributes' => array('autocomplete'=>'off'),
            'id'       => 'woooe_field_end_date'
        ),

        array(
            'name'     => __( 'Export Now!', 'woooe' ),
            'type'     => 'export_button',
            'id'       => 'woooe_field_export_now'
        ),

    
        array(
             'type' => 'sectionend',
             'id' => 'woooe_export_duration'
        ),

);

return apply_filters( 'woooe_settings_fields_general', array_merge($filename_section, $fields_section, $export_duration) );