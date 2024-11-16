<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Order_Export_Subscription {
    private $sub;
    private $sub_fields_all;
    private $sub_fields_active;


    function __construct() {
		$this->sub_fields['sub_status']		= array( 'string', __( 'Subscription Status', 'woocommerce-order-export' ) );
		$this->sub_fields['sub_start_date']	= array( 'string', __( 'Subscription Start Date', 'woocommerce-order-export' ) );
		$this->sub_fields['sub_next_payment']	= array( 'string', __( 'Subscription Next Payment', 'woocommerce-order-export' ) );
		$this->sub_fields['sub_last_order_date'] = array( 'string', __( 'Subscription Last Order Date', 'woocommerce-order-export' ) );
		$this->sub_fields['sub_num_renewals']	= array( 'number', __( 'Subscription Number of Renewals', 'woocommerce-order-export' ) );
		$this->sub_fields['sub_total_orders']	= array( 'number', __( 'Subscription Total Orders', 'woocommerce-order-export' ) );
		$this->sub_fields['sub_total_amount_paid']	= array( 'money', __( 'Subscription Total Amount Paid', 'woocommerce-order-export' ) );
		$this->sub_fields_all = array_keys($this->sub_fields);

		add_filter('woe_get_order_segments', array($this, 'add_order_segments'));
		add_filter('woe_get_order_fields_subscription', array($this, 'add_order_fields') );
		add_action('woe_order_export_started', array($this, 'get_subscription_details') );
		add_filter('woe_fetch_order_row', array($this, 'fill_new_columns'), 10, 2);
    }

    function add_order_segments($segments) {
		if (WC_Order_Export_Data_Extractor_UI::$object_type === 'shop_subscription') {
		    $segments['subscription'] = __( 'Subscription', 'woocommerce-order-export' );
		}
		return $segments;
    }

    function add_order_fields($fields) {
		foreach($this->sub_fields as $key=>$data)  {
			list($format,$label) = $data;
			$fields[$key]	= array('segment' => 'subscription', 'format' => $format, 'label' => $label);
		}
		return $fields;
    }

    function get_subscription_details($order_id) {
		if( !isset($this->sub_fields_active) ) {
			$this->sub_fields_active = array();
			foreach(WC_Order_Export_Engine::$current_job_settings["order_fields"] as $field) {
				if( isset($field['key']) AND in_array($field['key'], $this->sub_fields_all) )
					$this->sub_fields_active[$field['key']] = 1;					
			}
		}

	    $this->sub = array();

	    if(WC_Order_Export_Data_Extractor::$object_type === 'shop_subscription' && function_exists('wcs_get_subscription')) {
			$sub = wcs_get_subscription($order_id);
			if( $sub ) {
				if( isset($this->sub_fields_active['sub_status']) )
					$this->sub['sub_status']	= $sub->get_status();

				if( isset($this->sub_fields_active['sub_start_date']) )
					$this->sub['sub_start_date']	= date_i18n( wc_date_format(), $sub->get_time( 'date_created', 'site' ) );

				if( isset($this->sub_fields_active['sub_next_payment']) )
					$this->sub['sub_next_payment']	= $sub->get_time( 'next_payment_date', 'site' ) ? date_i18n( wc_date_format(), $sub->get_time( 'next_payment_date', 'site' ) ) : '-';

				if( isset($this->sub_fields_active['sub_last_order_date']) )
					$this->sub['sub_last_order_date'] = $sub->get_time( 'last_order_date_created', 'site' ) ? date_i18n( wc_date_format(), $sub->get_time( 'last_order_date_created', 'site' ) ) : '-';

				if( isset($this->sub_fields_active['sub_num_renewals']) )
					$this->sub['sub_num_renewals'] = count( array_unique( $sub->get_related_orders( 'ids', array('renewal') ) ) );

				if( isset($this->sub_fields_active['sub_total_orders']) )
					$this->sub['sub_total_orders'] = count( array_unique( $sub->get_related_orders( 'ids', 'any' ) ) );

				if( isset($this->sub_fields_active['sub_total_amount_paid']) ) {
					$this->sub['sub_total_amount_paid'] = 0;
					foreach($sub->get_related_orders( 'all', 'any' ) as $related_order) {
						if ( null !== $related_order->get_date_paid() ) 
							$this->sub['sub_total_amount_paid'] += $related_order->get_total();
					}
				}

			}	//if order has subscription 
	    }

	    return $order_id;
    }

    // add new values to row
    function fill_new_columns($row, $order_id) {
		foreach($this->sub as $k => $v) {
		    if(isset($row[$k])) {
				$row[$k] = $v;
		    }
		}
		return $row;
    }
}