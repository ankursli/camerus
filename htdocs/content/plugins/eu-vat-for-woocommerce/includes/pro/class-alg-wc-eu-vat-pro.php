<?php
/**
 * EU VAT for WooCommerce - Pro Class
 *
 * @version 1.7.0
 * @since   1.7.0
 * @author  WPWhale
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_EU_VAT_Pro' ) ) :

class Alg_WC_EU_VAT_Pro {

	/**
	 * Constructor.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function __construct() {
		add_filter( 'alg_wc_eu_vat_option',                      array( $this, 'alg_wc_eu_vat_option' ), PHP_INT_MAX, 3 );
		add_filter( 'alg_wc_eu_vat_settings',                    array( $this, 'settings' ), 10, 3 );
		add_filter( 'alg_wc_eu_vat_check_ip_location_country',   array( $this, 'check_ip_location_country' ) );
		add_filter( 'alg_wc_eu_vat_check_company_name',          array( $this, 'check_company_name' ) );
		add_filter( 'alg_wc_eu_vat_show_for_user_roles',         array( $this, 'show_for_user_roles' ) );
		add_filter( 'alg_wc_eu_vat_show_in_countries',           array( $this, 'show_in_countries' ) );
		add_filter( 'alg_wc_eu_vat_maybe_exclude_vat',           array( $this, 'maybe_exclude_vat' ) );
		add_filter( 'alg_wc_eu_vat_set_eu_vat_country_locale',   array( $this, 'set_eu_vat_country_locale' ), 10, 2 );
	}

	/**
	 * set_eu_vat_country_locale.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function set_eu_vat_country_locale( $country_locales, $show_in_countries ) {
		$show_eu_vat_field_countries = array_map( 'strtoupper', array_map( 'trim', explode( ',', $show_in_countries ) ) );
		// Disable field in existing locales
		foreach ( $country_locales as $country_code => &$country_locale ) {
			if ( ! in_array( $country_code, $show_eu_vat_field_countries ) ) {
				$country_locale[ alg_wc_eu_vat_get_field_id( true ) ] = array(
					'required' => false,
					'hidden'   => true,
				);
			}
		}
		// Enable field in selected locales
		$is_required = ( 'yes' === get_option( 'alg_wc_eu_vat_field_required', 'no' ) );
		foreach ( $show_eu_vat_field_countries as $country_code ) {
			$country_locales[ $country_code ][ alg_wc_eu_vat_get_field_id( true ) ] = array(
				'required' => $is_required,
				'hidden'   => false,
			);
		}
		return $country_locales;
	}

	/**
	 * maybe_exclude_vat.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function maybe_exclude_vat( $value ) {
		$preserve_base_country_check_passed = true;
		if ( 'no' != ( $preserve_option_value = get_option( 'alg_wc_eu_vat_preserve_in_base_country', 'no' ) ) ) {
			$selected_country = substr( alg_wc_eu_vat_session_get( 'alg_wc_eu_vat_to_check' ), 0, 2 );
			if ( ! ctype_alpha( $selected_country ) ) {
				$selected_country = '';
				if ( 'yes' === get_option( 'alg_wc_eu_vat_allow_without_country_code', 'no' ) ) {
					// Getting country from POST, or from the customer object
					if ( ! ctype_alpha( $selected_country ) ) {
						$selected_country = WC()->checkout->get_value( 'billing_country' );
					}
					// Fallback #1
					if ( ! ctype_alpha( $selected_country ) && ! empty( $_REQUEST['post_data'] ) ) {
						parse_str( $_REQUEST['post_data'], $post_data_args );
						if ( ! empty( $post_data_args['billing_country'] ) ) {
							$selected_country = sanitize_text_field( $post_data_args['billing_country'] );
						}
					}
					// Fallback #2
					if ( ! ctype_alpha( $selected_country ) && ! empty( $_REQUEST['billing_country'] ) ) {
						$selected_country = sanitize_text_field( $_REQUEST['billing_country'] );
					}
					// Fallback #3
					if ( ! ctype_alpha( $selected_country ) && ! empty( $_REQUEST['country'] ) ) {
						$selected_country = sanitize_text_field( $_REQUEST['country'] );
					}
				}
				if ( ! ctype_alpha( $selected_country ) ) {
					return false;
				}
			}
			$selected_country = strtoupper( $selected_country );
			if ( 'EL' === $selected_country ) {
				$selected_country = 'GR';
			}
			if ( 'yes' === $preserve_option_value ) {
				$location = wc_get_base_location();
				if ( empty( $location['country'] ) ) {
					$location = wc_format_country_state_string( apply_filters( 'woocommerce_customer_default_location', get_option( 'woocommerce_default_country' ) ) );
				}
				$preserve_base_country_check_passed = ( strtoupper( $location['country'] ) !== $selected_country );
			} elseif ( '' != get_option( 'alg_wc_eu_vat_preserve_in_base_country_locations', '' ) ) { // `list`
				$locations = array_map( 'strtoupper', array_map( 'trim', explode( ',', get_option( 'alg_wc_eu_vat_preserve_in_base_country_locations', '' ) ) ) );
				$preserve_base_country_check_passed = ( ! in_array( $selected_country, $locations ) );
			}
		}
		return $preserve_base_country_check_passed;
	}

	/**
	 * show_in_countries.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function show_in_countries( $value ) {
		return get_option( 'alg_wc_eu_vat_show_in_countries', '' );
	}

	/**
	 * show_for_user_roles.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function show_for_user_roles( $value ) {
		return get_option( 'alg_wc_eu_vat_show_for_user_roles', array() );
	}

	/**
	 * check_company_name.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function check_company_name( $value ) {
		return get_option( 'alg_wc_eu_vat_check_company_name', 'no' );
	}

	/**
	 * check_ip_location_country.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function check_ip_location_country( $value ) {
		return get_option( 'alg_wc_eu_vat_check_ip_location_country', 'no' );
	}

	/**
	 * settings.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function settings( $value, $type = '', $args = array() ) {
		return '';
	}

}

endif;

return new Alg_WC_EU_VAT_Pro();
