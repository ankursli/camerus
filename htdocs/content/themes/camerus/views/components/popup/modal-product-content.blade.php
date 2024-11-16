@if(!empty($product_id) && !empty($product))
    <div class="uk-grid-small" data-uk-grid>
        <div class="col-left uk-width-3-5@m">

            <div class="uk-grid-small" data-uk-grid>
                <div class="uk-width-5-6@m uk-width-3-4@s">

                    <div class="block block-product__slider">
                        <div class="block-content">
                            <div class="block-body slider" data-uk-lightbox>

                                <?php if(!empty($attachment_ids) && is_array($attachment_ids)) : ?>
                                <?php foreach ($attachment_ids as $attachment_id) : ?>
                                <a class="img-container img-middle" href="<?php echo wp_get_attachment_image_url($attachment_id, 'full') ?>">
                                    <?php echo wp_get_attachment_image($attachment_id, 'full', false, ['width' => '2177', 'height' => '2855']) ?>
                                </a>
                                <?php endforeach; ?>
                                <?php elseif($product->get_image_id()): ?>
                                <a class="img-container img-middle" href="<?php echo wp_get_attachment_image_url($post_thumbnail_id, 'full') ?>">
                                    <?php echo wp_get_attachment_image($post_thumbnail_id, 'full', false, ['width' => '2177', 'height' => '2855']) ?>
                                </a>
                                <?php else: ?>
                            <?php
                                    $html = '<div class="woocommerce-product-gallery__image--placeholder img-container img-middle">';
                                    $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')),
                                        esc_html__('Awaiting product image', 'woocommerce'));
                                    $html .= '</div>';

                                    echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html,
                                        $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                                    ?>
                            <?php endif; ?>

                            </div><!-- .block-body -->
                        </div><!-- .block-content -->
                    </div><!-- .block-product__slider -->

                </div>
                <div class="uk-width-1-6@m uk-width-1-4@s">


                    <div class="block block-product__nav">
                        <div class="block-content">
                            <div class="block-body slider">

                                <?php if ($attachment_ids && $product->get_image_id()) : ?>
                                <?php foreach ($attachment_ids as $attachment_id) : ?>

                                <figure class="img-container img-middle">
                                    <?php echo wp_get_attachment_image($attachment_id, 'full', false, ['width' => '2177', 'height' => '2855']) ?>
                                </figure>

                                <?php endforeach; ?>
                                <?php elseif($product->get_image_id()): ?>
                                <figure class="img-container img-middle">
                                    <?php echo wp_get_attachment_image($product->get_image_id(), 'full', false, ['width' => '2177', 'height' => '2855']) ?>
                                </figure>
                                <?php endif; ?>

                            </div><!-- .block-body -->

                            @if(!empty($schema_img))
                                <div data-uk-lightbox>
                                    <a href="{{ $schema_img['url'] }}" class="schema-block">
                                        <figure class="img-container img-middle">
                                            <?php echo wp_get_attachment_image($schema_img['ID'], 'thumbnail', false,
                                                ['width' => '2177', 'height' => '2855']) ?>
                                        </figure>
                                    </a>
                                </div>
                            @endif
                        </div><!-- .block-content -->
                    </div><!-- .block-product__nav -->


                </div>
            </div>

        </div>
        <div class="col-right uk-width-2-5@m">

            <div class="uk-grid-small" data-uk-grid>
                <div class="uk-width-3-4@m">
                    <div class="block block-product__title">
                        <div class="block-content">
                            <div class="block-header"><?php _e('Réf', THEME_TD); ?>. {{ $product->get_sku() }}</div><!-- .block-header -->
                            <div class="block-body">{{ $product->get_title() }}</div><!-- .block-body -->
                            <div class="block-footer">
                            </div><!-- .block-footer -->
                        </div><!-- .block-content -->
                    </div><!-- .block-product__title -->
                    <div class="block block-product__characteristics uk">
                        @if(!empty($color))
                            <form class="block-content" action="#">
                                <div class="block-body uk-grid uk-grid-small uk-flex-middle">
                                    <div class="uk-width-2-2 uk-flex uk-flex-middle">
                                        <strong class="label"><?php _e('Couleur', THEME_TD) ?></strong>
                                        <!-- <div class="value">
              <span class="ui-selectmenu-text"><span style="background:#ffffff" class="bg"></span>Blanc</span>
              </div> -->
                                        <div class="value">
                                    <span tabindex="0" id="product__characteristics-color-button" role="combobox" aria-expanded="false" aria-autocomplete="list"
                                          aria-owns="product__characteristics-color-menu" aria-haspopup="true"
                                          class="ui-selectmenu-button ui-button ui-widget ui-selectmenu-button-closed ui-corner-all"
                                          aria-activedescendant="ui-id-1" aria-labelledby="ui-id-1" aria-disabled="false"><span
                                                class="ui-selectmenu-icon icon icon-selectmenu-arrows ui-icon ui-icon-triangle-1-s"></span>
                                        <span class="ui-selectmenu-text">
                                            <span style="background:{{ $color['value'] }}" class="bg">
                                            </span>{{ $color['name'] }}
                                        </span>
                                    </span>
                                        </div>
                                    </div>
                                    <div class="uk-width-expand uk-text-right">
                                    </div>
                                </div><!-- .block-body -->
                            </form><!-- .block-content -->
                        @endif
                    </div><!-- .block-product__characteristics -->
                    <div class="block block-product__description">
                        <div class="block-content">
                            <div class="block-body">
                                <strong class="title"><?php _e('Description', THEME_TD) ?></strong>
                                <div class="summary">
                                    <div>
                                        <p>: </p>
                                        <p><?php echo $product->get_short_description(); ?></p>
                                    </div>
                                    <div style="padding-top: 20px;">
                                        <p><?php echo $product->get_description(); ?></p>
                                    </div>
                                    <p><?php _e('Existe en') ?> {{ count($colors) }} <?php _e('coloris') ?>.
                                </div>
                            </div><!-- .block-body -->
                        </div><!-- .block-content -->
                    </div><!-- .block-product__description -->
                    <div class="block block-product__attributes uk">
                        <div class="block-content">
                            <dl class="block-body">
                                <?php if(!empty($metas) && is_array($metas)) : ?>
                                <?php foreach ($metas as $meta) :?>
                                <dt><?php echo esc_attr($meta['product_options_title']) ?></dt>
                                <dd><?php echo esc_attr($meta['product_options_desc']) ?></dd>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </dl><!-- .block-body -->
                        </div><!-- .block-content -->
                    </div><!-- .block-product__attributes -->
                </div>
            </div>

        </div>
    </div>
@endif