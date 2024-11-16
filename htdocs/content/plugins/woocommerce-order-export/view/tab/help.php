<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user_guide_link = '<a href="https://docs.algolplus.com/order-export-docs/" target=_blank>' . __( 'user guide',
		'woocommerce-order-export' ) . '</a>';
$helpdesk_link = '<a href="https://algolplus.freshdesk.com" target=_blank>' . __( 'helpdesk system',
		'woocommerce-order-export' ) . '</a>';
$settings_link = '<a href="' . admin_url( 'admin.php?page=wc-order-export&tab=tools' ) . '" target=_blank>' . __( 'settings',
		'woocommerce-order-export' ) . '</a>';
$snippets_link = '<a href="https://algolplus.com/plugins/snippets-plugins/" target=_blank>' . __( 'code snippets',
		'woocommerce-order-export' ) . '</a>';
$samples_link  = '<a href="https://algolplus.com/plugins/code-samples/" target=_blank>' . __( 'this page',
		'woocommerce-order-export' ) . '</a>';
?>
<div class="weo_clearfix"></div>
<div id="woe-admin" class="container-fluid wpcontent">
    <br>
    <p>
     <?php echo sprintf( __( "Please, review %s at first.",'woocommerce-order-export' ), $user_guide_link ); ?>
     <br>
     <br>
     <?php echo sprintf( __( 'Need help? Create ticket in %s .', 'woocommerce-order-export' ), $helpdesk_link ); ?>
     <br>
     <br>
		<?php echo sprintf( __( "Don't forget to attach your %s or some screenshots. It will significantly reduce reply time :)",
			'woocommerce-order-export' ), $settings_link ); ?></p>
    <br>
    <p><?php echo sprintf( __( 'Look at %s for popular plugins or check %s to study how to extend the plugin.',
			'woocommerce-order-export' ), $snippets_link, $samples_link ); ?></p>
</div>