<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use WP_Widget;

class ProductSalon extends WP_Widget
{
    public function __construct()
    {
        defined('THEME_TD') ? THEME_TD : define('THEME_TD', env('APP_TD', 'camerus'));
        parent::__construct('product-salon', __('Salon Produit', THEME_TD), [
            'name' => __('Détails du salon produit', THEME_TD),
            'description' => __('Display a salon of the product.', THEME_TD)
        ]);
    }

    public function widget($args, $instance)
    {
        $datas = [];
        $salon = null;
        $post_type = get_query_var('post_type');
        $salon_input = Request::input(SLUG_EVENT_SALON_QUERY) ? Request::input(SLUG_EVENT_SALON_QUERY) : getEventSalonSlugInSession(SLUG_EVENT_SALON_QUERY);

        if (empty($salon_input)) {
            session()->forget(SLUG_EVENT_SESSION_SALON);
            return;
        }

        $salon_slug = $salon_input;

        if (isset($salon_slug) && ($post_type === 'product' || is_product_taxonomy())) {
            $salon_id = (int)$salon_slug;

            if (empty($salon_id)) {
                $the_slug = $salon_slug;
                $args = array(
                    'name' => $the_slug,
                    'post_type' => 'salon',
                    'post_status' => ['publish', 'private'],
                    'posts_per_page' => 1
                );
                $my_posts = get_posts($args);
                if ($my_posts) {
                    $salon_id = $my_posts[0]->ID;
                    $salon = $my_posts[0];
                }
            }

            if (empty($salon)) {
                $salon = get_post($salon_id);
            }

            if (!empty($salon)) {
                $datas['term_salon'] = $salon;
                $datas['view_link'] = true;
                $view = View::make('widgets.product-salon', $datas)->render();

                echo $view;
            }
        }
    }

    public function form($instance)
    {
        ?>
        <label for=""><?php _e('Afficher le salon du produit', THEME_TD) ?></label>
        <?php
    }

    public function update($new_instance, $old_instance)
    {

    }
}