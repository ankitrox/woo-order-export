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
 * Prepare data for export style - inline
 */
if(!function_exists('woooe_filter_record')){

    function woooe_filter_record($element, $export_style = 'inline'){

        if(is_array($element)){
            return ('inline' == $export_style) ? implode(' | ', $element) : $element[0];
        }

        return $element;
    }
}