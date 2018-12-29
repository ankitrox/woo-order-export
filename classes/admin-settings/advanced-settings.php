<?php

return apply_filters( 'woooe_settings_fields_advanced', array(

        'section_title' => array(
            'name'     => __( 'Advanced Settings', 'woooe' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'woooe_title'
        ),

        'section_end' => array(
             'type' => 'sectionend',
             'id' => 'woooe_title'
        )
    
));