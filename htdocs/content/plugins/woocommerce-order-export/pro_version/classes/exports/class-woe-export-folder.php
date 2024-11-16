<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WOE_Export_Folder extends WOE_Export {

	public function run_export( $filename, $filepath, $num_retries, $is_last_order = true ) {
		if ( empty( $this->destination['path'] ) ) {
			$this->destination['path'] = ABSPATH;
		}

		if ( preg_match( '#\.php$#i', $filename ) ) {
			return __( "Creating PHP files is prohibited.", 'woocommerce-order-export' );
		}

		if ( ! file_exists( $this->destination['path'] ) ) {
			if ( @ ! mkdir( $this->destination['path'], 0777, true ) ) {
				return sprintf( __( "Can't create folder '%s'. Check permissions.", 'woocommerce-order-export' ),
					$this->destination['path'] );
			}
		}
		if ( ! is_writable( $this->destination['path'] ) ) {
			return sprintf( __( "Folder '%s' is not writable. Check permissions.", 'woocommerce-order-export' ),
				$this->destination['path'] );
		}

		$output_filepath = $this->destination['path'] . "/" . $filename;
		if( file_exists($output_filepath ) and has_filter("woe_folder_file_append_function")) {
			if( !apply_filters("woe_folder_file_append_function", true, $filepath, $output_filepath) )
				return sprintf( __( "Can't append records to '%s'. Check permissions.", 'woocommerce-order-export' ),
					$this->destination['path'] );
		}
		elseif ( @ ! copy( $filepath, $output_filepath ) ) {
			return sprintf( __( "Can't export file to '%s'. Check permissions.", 'woocommerce-order-export' ),
				$this->destination['path'] );
		}

		$this->finished_successfully = true;
		do_action("woe_folder_file_created", $output_filepath);

		return sprintf( __( "File '%s' has been created in folder '%s'", 'woocommerce-order-export' ), $filename,
			$this->destination['path'] );
	}

}
