<?php do_action('woocommerce_before_account_navigation'); ?>

<aside class="col-sm-2 col-sm-offset-1">
    <!-- blocks -->

    <div class="uk-grid-small" data-uk-grid>

        <div class="block block-widget__spacenav uk-width-1-1 hidden-xs">
            <div class="block-content">
                <strong class="block-header"><?php _e('Mon espace client', THEME_TD) ?></strong><!-- .block-header -->
                <ul class="block-body">
					<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
					<?php
					$classes = wc_get_account_menu_item_classes($endpoint);
					$active_class = '';
					if (strpos($classes, 'is-active')) {
						$active_class = 'active';
					}
					?>

                    <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?> <?php echo $active_class; ?>">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
                    </li>

					<?php endforeach; ?>
                </ul>
                <div class="block-footer"></div><!-- .block-footer -->
            </div><!-- .block-content -->
        </div><!-- .block-widget__spacenav -->

    </div>

    <!-- end: blocks -->
</aside>

<?php do_action('woocommerce_after_account_navigation'); ?>
