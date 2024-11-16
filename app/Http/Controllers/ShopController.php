<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Themosis\Core\Forms\FormHelper;
use Themosis\Core\Validation\ValidatesRequests;
use Themosis\Support\Facades\Filter;
use WC_Cart;
use WC_Checkout;
use WC_Order;
use WC_Shortcode_Checkout;
use WP_Query;

class ShopController extends BaseController
{
    use FormHelper, ValidatesRequests;

    /**
     * indexShop for shop archive page
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexShop($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $metatitle = get_post_meta($page_id, '_yoast_wpseo_title', true);
        $metadesc = get_post_meta($page_id, '_yoast_wpseo_metadesc', true);

        if (empty($metatitle)) {
            $metatitle = get_the_title($page_id);
        }
        if (empty($metadesc)) {
            $metadesc = get_bloginfo('description');
        }
        $args['metatitle'] = $metatitle;
        $args['metadesc'] = $metadesc;

        $post_thumb = get_the_post_thumbnail_url($page_id);

        if (empty($post_thumb)) {
            $post_thumb = get_stylesheet_directory_uri()."/assets/images/head__logo-resoconfort.png";
        }

        $args['post_thumb'] = $post_thumb;

        //td($arg);

        return view('pages.default', $args);
    }

    /**
     * indexProduct for single product view
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexProduct($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $metatitle = get_post_meta($page_id, '_yoast_wpseo_title', true);
        $metadesc = get_post_meta($page_id, '_yoast_wpseo_metadesc', true);

        if (empty($metatitle)) {
            $metatitle = get_the_title($page_id);
        }
        if (empty($metadesc)) {
            $metadesc = get_bloginfo('description');
        }
        $args['metatitle'] = $metatitle;
        $args['metadesc'] = $metadesc;

        $post_thumb = get_the_post_thumbnail_url($page_id);

        if (empty($post_thumb)) {
            $post_thumb = get_stylesheet_directory_uri()."/assets/images/head__logo-resoconfort.png";
        }

        $args['post_thumb'] = $post_thumb;

        $request_uri = $_SERVER["REQUEST_URI"];
        $cpage = 1;

        if (preg_match("/page/i", $request_uri)) {
            $arr = explode("/", $request_uri);
            $cpage = $arr[count($arr) - 2];
        }

        $args["cpage"] = $cpage;

        $posts_args = array(
            "posts_per_page" => 6,
            "paged" => $cpage,
            "post_type" => "post",
            "post_status" => "publish",
            "order_by" => "date",
            "order" => "DESC"
        );

        $query = new WP_Query($posts_args);
        $posts = $query->posts;
        $args["posts"] = $posts;
        $total_pages = $query->max_num_pages;
        $args["total_pages"] = $total_pages;

        $terms = get_terms(array(
            "taxonomy" => "post_theme",
            "hide_empty" => "false"
        ));

        $args["terms"] = $terms;

        $faqs_args = [
            'post_type' => 'faqs',
            'post_status' => 'publish',
            'post_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        $query = new WP_Query($faqs_args);
        $faqs = $query->posts;
        $args["faqs"] = $faqs;

        $paginate_args = array(
            'base' => '%_%',
            'format' => '?paged=%#%',
            'total' => 1,
            'current' => 0,
            'show_all' => false,
            'end_size' => 1,
            'mid_size' => 2,
            'prev_next' => true,
            'type' => 'plain',
            'add_args' => false,
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );
        $post_paginate = paginate_links($paginate_args);

        return view('pages.actualites', $args);
    }

    /**
     * indexListingProduct controller
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexListingProduct($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $metatitle = get_post_meta($page_id, '_yoast_wpseo_title', true);
        $metadesc = get_post_meta($page_id, '_yoast_wpseo_metadesc', true);

        if (empty($metatitle)) {
            $metatitle = get_the_title($page_id);
        }
        if (empty($metadesc)) {
            $metadesc = get_bloginfo('description');
        }
        $args['metatitle'] = $metatitle;
        $args['metadesc'] = $metadesc;

        $post_thumb = get_the_post_thumbnail_url($page_id);

        if (empty($post_thumb)) {
            $post_thumb = get_stylesheet_directory_uri()."/assets/images/head__logo-resoconfort.png";
        }

        $args['post_thumb'] = $post_thumb;

        $request_uri = $_SERVER["REQUEST_URI"];
        $cpage = 1;

        if (preg_match("/page/i", $request_uri)) {
            $arr = explode("/", $request_uri);
            $cpage = $arr[count($arr) - 2];
        }

        $args["cpage"] = $cpage;

        $posts_args = array(
            "posts_per_page" => 6,
            "paged" => $cpage,
            "post_type" => "faqs",
            "post_status" => "publish",
            "order_by" => "date",
            "order" => "DESC"
        );

        $query = new WP_Query($posts_args);
        $posts = $query->posts;
        $args["posts"] = $posts;
        $total_pages = $query->max_num_pages;
        $args["total_pages"] = $total_pages;

        $terms = get_terms(array(
            "taxonomy" => "faqs_cat",
            "hide_empty" => "false"
        ));

        $args["terms"] = $terms;

        $args["no_slick"] = "no_slick";

        $paginate_args = array(
            'base' => '%_%',
            'format' => '?paged=%#%',
            'total' => 1,
            'current' => 0,
            'show_all' => false,
            'end_size' => 1,
            'mid_size' => 2,
            'prev_next' => true,
            'type' => 'plain',
            'add_args' => false,
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );
        $post_paginate = paginate_links($paginate_args);

        return view('pages.faqs-list', $args);
    }

    /**
     * indexCategory for Shop page
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexCategory($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $metatitle = get_post_meta($page_id, '_yoast_wpseo_title', true);
        $metadesc = get_post_meta($page_id, '_yoast_wpseo_metadesc', true);

        if (empty($metatitle)) {
            $metatitle = get_the_title($page_id);
        }
        if (empty($metadesc)) {
            $metadesc = get_bloginfo('description');
        }
        $args['metatitle'] = $metatitle;
        $args['metadesc'] = $metadesc;

        $post_thumb = get_the_post_thumbnail_url($page_id);

        if (empty($post_thumb)) {
            $post_thumb = get_stylesheet_directory_uri()."/assets/images/head__logo-resoconfort.png";
        }

        $args['post_thumb'] = $post_thumb;

        $request_uri = $_SERVER["REQUEST_URI"];
        $cpage = 1;

        if (preg_match("/page/i", $request_uri)) {
            $arr = explode("/", $request_uri);
            $cpage = $arr[count($arr) - 2];
        }

        $args["cpage"] = $cpage;

        $posts_args = array(
            "posts_per_page" => 6,
            "paged" => $cpage,
            "post_type" => "faqs",
            "post_status" => "publish",
            "order_by" => "date",
            "order" => "DESC"
        );

        $query = new WP_Query($posts_args);
        $posts = $query->posts;
        $args["posts"] = $posts;
        $total_pages = $query->max_num_pages;
        $args["total_pages"] = $total_pages;

        $terms = get_terms(array(
            "taxonomy" => "faqs_cat",
            "hide_empty" => "false"
        ));

        $args["terms"] = $terms;

        $args["no_slick"] = "no_slick";

        $paginate_args = array(
            'base' => '%_%',
            'format' => '?paged=%#%',
            'total' => 1,
            'current' => 0,
            'show_all' => false,
            'end_size' => 1,
            'mid_size' => 2,
            'prev_next' => true,
            'type' => 'plain',
            'add_args' => false,
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );
        $post_paginate = paginate_links($paginate_args);

        return view('pages.single-need', $args);
    }

    /**
     * @author RJ
     * To handle order pay checkout process
     */
    public function indexOrderPay($page, $query)
    {
        Filter::add('document_title_parts', function ($parts) {
            $parts['title'] = WC()->query->get_endpoint_title('order-pay');
            return $parts;
        });

        $args = [];
        $order_id = (int)$query->query_vars['order-pay'];

        if (! empty($order_id)) {
            $args['order_id'] = absint($order_id);
            $order = wc_get_order($order_id);
            $args['order'] = $order;
            $args['totals'] = $order->get_order_item_totals();
            $args['order_button_text'] = apply_filters('woocommerce_pay_order_button_text', __('Pay for order', 'woocommerce'));

            return view('shop.checkout.form-pay', $args);
        }
        return redirect('/');
    }

    /**
     * @author Rova
     * To handle paiement checkout process
     */
    public function indexPaiementCheckout()
    {
        $args = [];
        $oCart = new WC_Cart();
        $ocheckout = new WC_Checkout();
        $oWC_order = new WC_Order();
        $is_logged = 0;
        if (is_user_logged_in()) {
            $is_logged = 1;
        }

        $salon_ID = '';
        $salon_title = '';
        $salon_lieu = '';
        $salon_date = '';
        $salon_end_date = '';
        $salon_ville = '';
        $salon_place = '';
        /**==================
         * About salon
         * ===================*/
        $current_salon = getEventSalonObjectInSession();
        $salon_slug = getEventSalonSlugInSession();
        $reed_data = getReedDataInfo();

        $reed_user_email = '';
        $salon_ville = '';
        $num_stand = '';
        $allee = '';
        $hall = '';
        if (! empty($reed_data)) {
            $reed_user_email = $reed_data->Email;
            $salon_ville = $reed_data->Ville ?? '';
            $n_stand = $reed_data->NumStand;
            if (! empty($n_stand)) {
                $stand1 = explode('-', $n_stand);
                if (count($stand1) > 1) {
                    $hall = $stand1[0];
                    $allee = preg_replace('/[0-9]+/', '', $stand1[1]);
                    $num_stand = (int)filter_var($stand1[1], FILTER_SANITIZE_NUMBER_INT);
                } else {
                    $allee = preg_replace('/[0-9]+/', '', $n_stand);
                    $num_stand = (int)filter_var($n_stand, FILTER_SANITIZE_NUMBER_INT);
                }
            }
        }

        if (empty($current_salon) && ! empty($reed_data->Salon)) {
            $current_salon = getSalonByRef($reed_data->Salon);
            $salon_slug = $current_salon->post_name;
        }

        if ($current_salon) {
            $salon_ID = $current_salon->ID;
            $salon_title = $current_salon->post_title;
            $salon_lieu = get_field('salon_address', $salon_ID);
            $salon_date = get_field('salon_start_date', $salon_ID);
            $salon_end_date = get_field('salon_end_date', $salon_ID);
            if (! empty($salon_end_date) && ICL_LANGUAGE_CODE == 'fr') {
                $salon_date = date('d-m-Y', strtotime($salon_date));
                $salon_end_date = date('d-m-Y', strtotime($salon_end_date));
            } else {
                $salon_date = date('m-d-Y', strtotime($salon_date));
                $salon_end_date = date('m-d-Y', strtotime($salon_end_date));
            }
            $salon_ville_id = get_field('salon_ville', $salon_ID);
            if (! empty($salon_ville_id)) {
                $salon_ville_obj = get_term($salon_ville_id, 'salon_city');
                if (! empty($salon_ville_obj)) {
                    $salon_ville = $salon_ville_obj->name;
                }
            }
            $salon_place = get_field('salon_place', $salon_ID);
        }

        /**=====================
         * About checkout & cart
         * ====================*/
        $CartCount = $oCart->get_cart_contents_count();
        $totalMount = $oCart->get_cart_contents_total();
        $assurance = $oCart->get_cart_total();
        $totalHT = $oCart->get_total_tax();
        $tva = $oCart->get_taxes_total();
        $cartTotal = $oCart->get_cart_contents_total();


        $args = [
            'checkout' => $ocheckout,
            'cartCount' => $CartCount,
            'MontTotal' => $totalMount,
            'assurance' => $assurance,
            'totalHT' => $totalHT,
            'tva' => $tva,
            'cartTotal' => $cartTotal,
            'salon_title' => $salon_title,
            'salon_lieu' => $salon_lieu,
            'salon_date' => $salon_date,
            'salon_end_date' => $salon_end_date,
            'salon_ville' => $salon_ville,
            'salon_place' => $salon_place,
            'salon_slug' => $salon_slug,
            'current_stape' => 2,
            'is_logged' => $is_logged,
            'current_salon' => $current_salon,
            'reed_user_email' => $reed_user_email,
            'num_stand' => $num_stand,
            'hall' => $hall,
            'allee' => $allee,
        ];

        //dd($args);

        return view('shop.form-checkout', $args);
    }

    public function indexShowroom($page, $query)
    {
        global $wp_query, $sitepress;

        $sitepress->switch_lang(ICL_LANGUAGE_CODE);

        $slugevent = get_query_var('showroom-name');
        $slugevent = wc_clean($slugevent);
        $taxonomy = get_query_var('showroom-category');
        $category = get_query_var('showroom-category-name');
        $subcategory = get_query_var('showroom-subcategory');
        $pageslug = get_query_var('showroom-page');
        $paged = get_query_var('showroom-page-number');
//        $salon_id = getEventSalonId($slugevent);

        if (!empty($slugevent)) {
            $args = array(
                'name' => $slugevent,
                'post_type' => 'salon',
                'post_status' => ['publish', 'private'],
                'posts_per_page' => 1,
                'suppress_filters' => false
            );
            $my_posts = get_posts($args);
            if ($my_posts) {
                $salon_id = $my_posts[0]->ID;
            }
        }

        if (! empty(cmrs_check_if_slug_exists($slugevent, 'salon'))) {
            $args = [];
            $product_args = [
                'post_type' => 'product',
                'post_status' => ['publish'],
                'posts_per_page' => 9,
                'orderby' => 'name',
                'order' => 'ASC',
                'wc_query' => 'product_query'
            ];

            if (! empty($taxonomy) && $taxonomy === 'page' && ! empty($category)) {
                $product_args['paged'] = (int)$category;
            } else {
                if (! empty($subcategory) && $subcategory === 'page' && ! empty($pageslug)) {
                    $product_args['paged'] = (int)$pageslug;
                } else {
                    if (! empty($pageslug) && $pageslug === 'page') {
                        $product_args['paged'] = (int)$paged;
                    }
                }
            }

            if (! empty($subcategory) && $subcategory !== 'page') {
                $product_args['tax_query'] = [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => [$subcategory],
                        'operator' => 'IN'
                    ]
                ];
            } else {
                if ((empty($subcategory) || $subcategory === 'page') && ! empty($category) && ! empty($pageslug)) {
                    $product_args['tax_query'][] = [
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => [$category],
                        'operator' => 'IN'
                    ];
                }
            }

            if (! empty($slugevent) && ! empty($salon_id) && empty($subcategory) && (empty($taxonomy) || $taxonomy === 'page')) {
                $product_ids = [];
                $salon_products = get_field('salon_products', $salon_id);

                if (! empty($salon_products) && is_array($salon_products)) {
                    foreach ($salon_products as $salon_product) {
                        $product_ids[] = $salon_product->ID;
                    }
                }
                if (! empty($product_ids)) {
                    $product_args['post__in'] = $product_ids;
                }
            }

            if (! empty($salon_id)) {
                $salon_hide_product = getSalonHiddenProduct();
                if (! empty($salon_hide_product)) {
                    $product_args['post__not_in'] = $salon_hide_product;
                }
            }

            $wp_query = new WP_Query($product_args);

            Filter::add('wpseo_title', function ($title) {
                $title = 'Showroom '.__('Produits', THEME_TD).' - '.SITE_MAIN_SYS_NAME;
                return $title;
            }, 15);

            if (! empty($slugevent)) {
                return view('shop.archive', $args);
            }
        }

        return redirect(get_permalink(ID_LIST_SALON));
    }

    public function indexStyleroom($page, $query)
    {
        global $wp_query;

        $event_slug = get_query_var('styleroom-name');
        $style_slug = get_query_var('styleroom-category');
        $pagination = get_query_var('styleroom-category-name');
        $paged = get_query_var('styleroom-subcategory');

        if (! empty($event_slug) && empty($style_slug)) {
            $event_slug = wc_clean($event_slug);
            $salon = cmrs_get_post_by_slug($event_slug, 'salon');
            if (! empty($salon)) {
                $is_active_style = get_field('is_style_active', $salon->ID);
                if (! empty($is_active_style)) {
                    $styles = get_field('styles', $salon->ID);
                    if (! empty($styles)) {
                        $style_list = [];
                        foreach ($styles as $key => $style) {
                            if (isset($style['style'])) {
                                $style_item = get_term($style['style'], 'salon_style');
                                if (! empty($style_item)) {
                                    $style_list[$key]['style_title'] = $style_item->name;
                                    $style_list[$key]['thumbnail_image'] = get_field('thumbnail_image', 'salon_style_'.$style['style']);
                                    $style_list[$key]['product_link'] = styleroomGetProductUrl($style_item->slug, $event_slug);
                                }
                            }
                        }
                        $args['styles'] = $style_list;

                        return view('shop.room.styleroom', $args);
                    }
                }
            }
        }

        if (! empty($event_slug) && ! empty($style_slug)) {
            $event_slug = wc_clean($event_slug);
            $salon = cmrs_get_post_by_slug($event_slug, 'salon');
            if (! empty($salon)) {
                $is_active_style = get_field('is_style_active', $salon->ID);
                if (! empty($is_active_style)) {
                    $styles = get_field('styles', $salon->ID);
                    if (! empty($styles)) {
                        $products_ids = [];
                        foreach ($styles as $key => $style) {
                            if (! empty($style['style'])) {
                                $style_item = get_term($style['style'], 'salon_style');
                                if (! empty($style_item) && $style_item->slug === $style_slug && ! empty($style['style_products'])) {
                                    foreach ($style['style_products'] as $style_product) {
                                        $products_ids[] = $style_product->ID;
                                    }
                                }
                            }
                        }
                    }

                    $args = [];
                    $product_args = [
                        'post_type' => 'product',
                        'post_status' => ['publish'],
                        'posts_per_page' => 9,
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'wc_query' => 'product_query'
                    ];

                    if ($pagination === 'page' && ! empty($paged)) {
                        $product_args['paged'] = (int)$paged;
                    }

                    if (! empty($products_ids)) {
                        $product_args['post__in'] = $products_ids;
                    }

                    $wp_query = new WP_Query($product_args);

                    Filter::add('wpseo_title', function ($title) {
                        $title = 'Styleroom '.__('Produits', THEME_TD).' - '.SITE_MAIN_SYS_NAME;
                        return $title;
                    }, 15);

                    if (! empty($event_slug)) {
                        return view('shop.archive', $args);
                    }
                }
            }
        }

        return redirect(get_permalink(ID_LIST_SALON));
    }
}


