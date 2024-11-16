<?php

namespace App\Hooks;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Themosis\Hook\Hookable;
use Themosis\Support\Facades\PostType;
use Themosis\Support\Facades\Taxonomy;
use WP_Query;

class Product extends Hookable
{

    private static $postType = 'product';
    private static $Products = [];

    /**
     * Register Hook
     */
    public function register()
    {
        Taxonomy::make('product_material', self::$postType, __('Matières', APP_TD), __('Matière', APP_TD))
            ->setLabels([
                'add_item'              => __('Ajouter une Matière', APP_TD),
                'edit_item'             => __('Modifier la Matière', APP_TD),
                'new_item'              => __('Nouvelle Matière', APP_TD),
                'view_item'             => __('Voir la Matière', APP_TD),
                'view_items'            => __('Voir les Matières', APP_TD),
                'all_items'             => __('Tous les Matière', APP_TD),
                'search_items'          => __('Rechercher dans les Matières', APP_TD),
                'not_found'             => __('Aucune Matière', APP_TD),
                'add_new'               => __('Ajouter une Matière', APP_TD),
                'add_new_item'          => __('Ajouter une nouvelle Matière', APP_TD),
                'insert_into_item'      => __('Insérer dans la Matière', APP_TD),
                'uploaded_to_this_item' => __('Uploader dans la Matière', APP_TD)
            ])
            ->setArguments([
                'public'             => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'show_in_nav_menus'  => true,
                'hierarchical'       => true,
                'show_tagcloud'      => false,
                'show_in_quick_edit' => false,
                'show_meta_cb'       => false,
                'meta_box_cb'        => false,
            ])
            ->set();

        Taxonomy::make('product_dotation_type', self::$postType, __('Types de dotation', APP_TD), __('Type de dotation', APP_TD))
            ->setLabels([
                'add_item'              => __('Ajouter une type de dotation', APP_TD),
                'edit_item'             => __('Modifier la type de dotation', APP_TD),
                'new_item'              => __('Nouvelle type de dotation', APP_TD),
                'view_item'             => __('Voir la type de dotation', APP_TD),
                'view_items'            => __('Voir les types de dotation', APP_TD),
                'all_items'             => __('Tous les types de dotation', APP_TD),
                'search_items'          => __('Rechercher dans les types de dotation', APP_TD),
                'not_found'             => __('Aucune type de dotation', APP_TD),
                'add_new'               => __('Ajouter une type de dotation', APP_TD),
                'add_new_item'          => __('Ajouter une nouvelle type de dotation', APP_TD),
                'insert_into_item'      => __('Insérer dans la type de dotation', APP_TD),
                'uploaded_to_this_item' => __('Uploader dans la type de dotation', APP_TD)
            ])
            ->setArguments([
                'public'             => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'show_in_nav_menus'  => true,
                'hierarchical'       => true,
                'show_tagcloud'      => false,
                'show_in_quick_edit' => false,
                'show_meta_cb'       => false,
                'meta_box_cb'        => false,
            ])
            ->set();
    }

    /**
     * Query Product post type
     *
     * @param  array  $args
     *
     * @return WP_Query
     */
    public static function query($args = []): WP_Query
    {
        $defaults = [
            'post_type'        => self::$postType,
            'posts_per_page'   => -1,
            'order'            => 'DESC',
            'orderby'          => 'date',
            'suppress_filters' => false,
        ];

        $post_args = wp_parse_args($args, $defaults);
        $query = new WP_Query($post_args);

        wp_reset_postdata();
        wp_reset_query();

        return $query;
    }

    /**
     * @param  array  $args
     *
     * @return array
     */
    public static function getCategories($args = []): array
    {
        $defaults = [
            'taxonomy'   => 'product_cat',
            'parent'     => 0,
            'hide_empty' => true,
            'exclude' => [15, 475, 476, 353, 'uncategorized', 'uncategorized-en', 'uncategorized-es'],
        ];
        $cat_args = wp_parse_args($args, $defaults);
        $categories = get_terms($cat_args);

        return $categories;
    }

    /**
     * @param  array  $args
     *
     * @return array
     */
    public static function getTags($args = []): array
    {
        $tags = get_terms(array(
            'taxonomy'   => 'product_tag',
            'parent'     => 0,
            'hide_empty' => true,
        ));

        return $tags;
    }

    /**
     * @param  array  $args
     *
     * @return array
     */
    public static function getColors($args = []): array
    {
        $colors = get_terms(array(
            'taxonomy'   => 'pa_color',
            'parent'     => 0,
            'hide_empty' => true,
        ));

        return $colors;
    }

    /**
     * @param  array  $args
     *
     * @return array
     */
    public static function getMaterials($args = []): array
    {
        $materials = get_terms(array(
            'taxonomy'   => 'product_material',
            'parent'     => 0,
            'hide_empty' => true,
        ));

        return $materials;
    }

    /**
     * @param  array  $args
     *
     * @return array
     */
    public static function getDotationTypes($args = []): array
    {
        $types = get_terms(array(
            'taxonomy'   => 'product_dotation_type',
            'parent'     => 0,
            'hide_empty' => false,
        ));

        return $types;
    }
}