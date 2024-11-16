<?php

namespace App\Hooks;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Themosis\Hook\Hookable;
use Themosis\Support\Facades\PostType;
use Themosis\Support\Facades\Taxonomy;
use WP_Query;

class Salon extends Hookable
{

    private static $postType = 'salon';
    private static $salons = [];

    /**
     * Register Hook
     */
    public function register()
    {
        PostType::make(self::$postType, __('Salons', APP_TD), __('Salon', APP_TD))
            ->setLabels([
                'add_item' => __('Ajouter un Salon', APP_TD),
                'edit_item' => __('Modifier le Salon', APP_TD),
                'new_item' => __('Nouvelle Salon', APP_TD),
                'view_item' => __('Voir le Salon', APP_TD),
                'view_items' => __('Voir les Salons', APP_TD),
                'all_items' => __('Tous les Salons', APP_TD),
                'search_items' => __('Rechercher dans les Salons', APP_TD),
                'not_found' => __('Aucun Salon', APP_TD),
                'add_new' => __('Ajouter un Salon', APP_TD),
                'add_new_item' => __('Ajouter un nouveau Salon', APP_TD),
                'insert_into_item' => __('Insérer dans le Salon', APP_TD),
                'uploaded_to_this_item' => __('Uploader dans le Salon', APP_TD)
            ])
            ->setArguments([
                'public' => true,
                'menu_position' => 56,
                'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt'],
                'rewrite' => ['slug' => 'salon', 'with_front' => false],
                'capability_type' => 'post',
                'query_var' => true,
                'menu_icon' => 'dashicons-admin-multisite',
                'has_archive' => false,
            ])
            ->set();

        Taxonomy::make('salon_city', 'salon', __('Villes', APP_TD), __('Ville', APP_TD))
            ->setLabels([
                'add_item' => __('Ajouter une ville', APP_TD),
                'edit_item' => __('Modifier la ville', APP_TD),
                'new_item' => __('Nouvelle ville', APP_TD),
                'view_item' => __('Voir la ville', APP_TD),
                'view_items' => __('Voir les villes', APP_TD),
                'all_items' => __('Tous les ville', APP_TD),
                'search_items' => __('Rechercher dans les villes', APP_TD),
                'not_found' => __('Aucune ville', APP_TD),
                'add_new' => __('Ajouter une ville', APP_TD),
                'add_new_item' => __('Ajouter une nouvelle ville', APP_TD),
                'insert_into_item' => __('Insérer dans la ville', APP_TD),
                'uploaded_to_this_item' => __('Uploader dans la ville', APP_TD)
            ])
            ->setArguments([
                'public' => true,
                'show_in_nav_menus' => false,
                'hierarchical' => false,
                'show_tagcloud' => false,
                'show_in_quick_edit' => true,
                'show_meta_cb' => false,
                'show_ui' => false
            ])
            ->set();

        Taxonomy::make('salon_style', self::$postType, __('Styles', APP_TD), __('Style', APP_TD))
            ->setLabels([
                'add_item'              => __('Ajouter un Style', APP_TD),
                'edit_item'             => __('Modifier le Style', APP_TD),
                'new_item'              => __('Nouvelle Style', APP_TD),
                'view_item'             => __('Voir le Style', APP_TD),
                'view_items'            => __('Voir les Styles', APP_TD),
                'all_items'             => __('Tous les Styles', APP_TD),
                'search_items'          => __('Rechercher dans les Styles', APP_TD),
                'not_found'             => __('Aucun Style', APP_TD),
                'add_new'               => __('Ajouter un Style', APP_TD),
                'add_new_item'          => __('Ajouter un nouveau Style', APP_TD),
                'insert_into_item'      => __('Insérer dans le Style', APP_TD),
                'uploaded_to_this_item' => __('Uploader dans le Style', APP_TD)
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

//        self::$salons = self::getSalon();
    }

    /**
     * Query Salon post type
     *
     * @param array $args
     *
     * @return array
     */
    public static function getSalon($args = []): array
    {
        $salon = [];

        $defaults = [
            'post_type' => self::$postType,
            'numberposts' => -1,
            'order' => 'DESC',
            'orderby' => 'date',
            'suppress_filters' => false
        ];

        $post_args = wp_parse_args($args, $defaults);
        $posts = get_posts($post_args);

        wp_reset_postdata();

        if (!empty($posts) && is_array($posts)) {
            foreach ($posts as $post) {
                if(function_exists('get_field')){
                    $meta = get_fields($post->ID);
                }
                if (!empty($meta) && is_array($meta)) {
                    foreach ($meta as $kmt => $mt) {
                        $post->$kmt = $mt;
                    }
//                    $post->product_url = get_permalink(wc_get_page_id('shop')) . '?' . SLUG_EVENT_SALON_QUERY . '=' . $post->post_name . '&salon-filter='
//                        . $post->post_name;
                    $post->product_url = showroomGetUrl($post->post_name);
                    $current_time = time();
                    $start_date = strtotime('midnight', strtotime($meta['salon_start_date']));
                    $endOfDay = strtotime("tomorrow", $start_date) - 1;

                    if ($endOfDay > $current_time) {
                        $key = $meta['salon_start_date'] . '--' . $post->ID;
                        $salon[$key] = $post;
                    }

                    $post->banner_img = get_the_post_thumbnail_url(getPageIDByTemplateName('agenda-template', true), 'full');
                    if (!empty($post->salon_banner_img)) {
                        $post->banner_img = wp_get_attachment_image_url($post->salon_banner_img['ID'], 'full');
                    }

                    if (array_key_exists('salon_ville', $meta) && !empty($meta['salon_ville'])) {
                        $salon_ville = get_term($meta['salon_ville'], 'salon_city');
                        $post->salon_ville_name = $salon_ville->name;
                    }
                    if (array_key_exists('salon_city_rate', $meta) && !empty($meta['salon_city_rate'])) {
                        $salon_ville = get_term($meta['salon_city_rate'], 'pa_city');
                        $post->salon_city_rate_slug = $salon_ville->slug;
                    }
                }
            }

            ksort($salon);
        }

        wp_reset_query();

        return $salon;
    }

    /**
     * @param array $args
     *
     * @return array
     */
    public static function getAgendaMonth($args = []): array
    {
        $country_code = ICL_LANGUAGE_CODE . '_' . strtoupper(ICL_LANGUAGE_CODE);
        setlocale(LC_TIME, $country_code . '.utf8');
        $salons = self::getSalon();
        $months = [];

        if (!empty($salons) && is_array($salons)) {
            foreach ($salons as $salon) {
                $date = date('Y-m', strtotime($salon->salon_start_date));
                Carbon::setLocale(ICL_LANGUAGE_CODE);
                $months[$date] = Carbon::createFromFormat('Y-m-d', $salon->salon_start_date);
            }
        }

        return $months;
    }

    /**
     * @param array $args
     *
     * @return array
     */
    public static function getAgendaCity($args = []): array
    {
//        $salons = self::getSalon();
//        $cities = [];
//
//        if (!empty($salons) && is_array($salons)) {
//            foreach ($salons as $salon) {
//                if (isset($salon->salon_ville) && !empty($salon->salon_city_rate)) {
//                    $cities[] = get_term($salon->salon_ville, 'salon_city');
//                }
//            }
//        }

        $cities = get_terms(array(
            'taxonomy' => 'salon_city',
            'hide_empty' => false,
        ));

//		dd($cities);

        return $cities;
    }

    /**
     * @param array $args
     *
     * @return array
     */
    public static function getAgendaCityRate($args = []): array
    {
        $cities = get_terms(array(
            'taxonomy' => 'pa_city',
            'hide_empty' => false,
        ));

        $all_city = [];
        if (!empty($cities)) {
            foreach ($cities as $city) {
                $hide = get_field('app_price_range_view', 'pa_city_' . $city->term_id);
                if (empty($hide)) {
                    $all_city[] = $city;
                }
            }
        }

//		dd($cities);

        return $all_city;
    }
}