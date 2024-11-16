<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
global $product, $post, $current_user;

$user_salons = getSalonUserFavoris();

?>
<div class="col-sm-8">

    <div class="uk-grid-small" data-uk-grid>
        <!-- blocks -->

        <div class="block block-events__list uk-width-1-1">
            <div class="block-content">
                <div class="block-body uk-grid uk-grid-small uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l">

                    @if(!empty($user_salons) && is_array($user_salons))

                        @foreach($user_salons as $id => $u_salon)
							<?php $salon = get_post($id); ?>

                            <div class="card card-events__item agenda-{{ $id }}">
                                <div class="card-content">
                                    <a class="card-body" href="{{ get_permalink($id) }}"
                                       title="{{ $salon->post_title }}">
                                        <figure class="img-container">
                                            {!! get_the_post_thumbnail($id, 'full', ['width' => '161', 'height' => '65']) !!}
                                        </figure>
                                        <h2 class="title">{!! $salon->post_title !!}</h2>
                                    </a><!-- .card-body -->
                                    <div class="card-footer agenda-btn">
                                        <button type="submit" class="btn btn-tt_u agenda-btn-favoris agenda-delete-favoris agenda-{{ $id }}"
                                                data-salon="agenda-{{ $id }}"
                                                data-secu="{{ wp_create_nonce('salon-agenda-' . $id) }}">
                                            <span><?php _e('SUPPRIMER', THEME_TD) ?></span>
                                        </button>
                                    </div><!-- .card-footer -->
                                </div><!-- .card-content -->
                            </div><!-- .card-events__item -->

                        @endforeach
                    @endif

                </div><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-events__list -->

        <!-- end: blocks -->


    </div><!-- .col -->

</div>