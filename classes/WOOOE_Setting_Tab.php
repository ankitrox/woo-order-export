<?php
if(!defined('ABSPATH')){
    exit;
}

if( !class_exists('WOOE_Setting_Tab') ){

    class WOOOE_Setting_Tab extends WC_Settings_Page {

        //Constructor
        function __construct() {

            $this->id       = 'woooe';
            $this->label    = __( 'Order Export', 'woooe' );
            parent::__construct();

            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 200 );
            add_filter( 'woocommerce_settings_tabs_woooe', array( $this, 'settings' ) );
            add_filter( 'woocommerce_update_options_woooe', array($this, 'update_settings') );
            add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
            add_action( 'woocommerce_admin_field_export_button', array( $this, 'export_button' ) );
        }

        /*
         * Adds setting tab to woocommerce setting page
         */
        function add_settings_tab( $settings_tabs ){
            $settings_tabs['woooe'] = __( 'Order Export', 'woooe' );
            return $settings_tabs;
        }

        /*
         * Add settings fields to settings tab
         */
        function settings() {

            global $current_section, $woooe;

            if(in_array( $current_section, array('', 'general')) ){
                woocommerce_admin_fields( $woooe->settings['general'] );
            }

            if( 'advanced' == $current_section ){
                woocommerce_admin_fields( $woooe->settings['advanced'] );
            }
        }

        /*
         * Save settings
         */
        function update_settings(){
            global $current_section, $woooe;
            
            if(in_array( $current_section, array('', 'general')) ){
                woocommerce_update_options( $woooe->settings['general'] );
            }
            
            if( 'advanced' == $current_section ){
                woocommerce_update_options( $woooe->settings['advanced'] );
            }
        }

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
                $sections['general'] = __('General', 'woooe');
                $sections['advanced'] = __('Advanced', 'woooe');
		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Output sections.
	 */
	public function output_sections() {
		global $current_section;

		$sections = $this->get_sections();

		if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
			return;
		}

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';
	}
        
        /*
         * Renders export button
         */
        function export_button($value){?>
            
            <tr valign="top">
                <th></th>
                <td class="forminp">
                    <input class="button btn" id="<?php echo $value['id']; ?>" type="button" value="<?php echo $value['name']; ?>" />
                    <div id="woooe-loader" style="margin-top: 10px; display: none;"><img src="<?php echo WOOOE_BASE_URL.'/assets/img/ajaxloader.gif' ?>" alt="<?php _e('Please wait...', 'woooe') ?>" /></div>
                </td>
            </tr><?php
        }
    }
}