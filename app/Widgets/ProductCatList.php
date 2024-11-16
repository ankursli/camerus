<?php

namespace App\Widgets;

use App\Hooks\Product;
use Illuminate\Support\Facades\View;
use Themosis\Support\Facades\Route;
use WP_Widget;

class ProductCatList extends WP_Widget
{
    public function __construct()
    {
        defined('THEME_TD') ? THEME_TD : define('THEME_TD', env('APP_TD', 'camerus'));
        parent::__construct('product-cat-list', __('Catégorie Produit', THEME_TD), [
            'name'        => __('Liste des catégories produits', THEME_TD),
            'description' => __('Display a list of the category product.', THEME_TD)
        ]);
    }

    public function widget($args, $instance)
    {
        global $post, $product;

        $datas = [];

        $cat_args = array(
            'parent' => 0,
        );

        $product_categories = Product::getCategories($cat_args);
        if (!empty($instance) && array_key_exists('title', $instance)) {
            $datas['title'] = apply_filters('widget_title', $instance['title']);
            if (Route::currentRouteName() == 'showroom-template') {
                $datas['title'] = __('Retour vers', THEME_TD) . ' ' . $datas['title'];
            }
        }

        $category = get_queried_object();
        if (!empty($category) && is_tax()) {
            $datas['current_term'] = (int) $category->term_id;
        }

        if (!empty($product_categories)) {
            $datas['product_categories'] = $product_categories;
            $view = View::make('widgets.product-cat-list', $datas)->render();

            echo $view;
        }
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Tout les produits', THEME_TD); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
}
