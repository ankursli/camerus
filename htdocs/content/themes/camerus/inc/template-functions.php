<?php

use App\DownloadStats;
use App\Library\Services\RentOrderManager;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Field;
use Themosis\Support\Facades\Filter;
use Themosis\Support\Facades\Metabox;

/**
 * Define constant page listing ID
 */
Action::add('init', function () {
    /**
     * Need to update ACF option page
     */
    $lp = [];
    if (function_exists('get_field')) {
        $lp = get_field('app_listing_pages', 'option');
    }
    define('ID_LIST_SALON', ! empty($lp['app_listing_page_salon'])
        ? getPostTranslatedID($lp['app_listing_page_salon']->ID, ICL_LANGUAGE_CODE, 'page') : getPostTranslatedID(95, ICL_LANGUAGE_CODE, 'page'));
    define('ID_LIST_POST', ! empty($lp['app_listing_page_post'])
        ? getPostTranslatedID($lp['app_listing_page_post']->ID, ICL_LANGUAGE_CODE, 'page') : getPostTranslatedID(78, ICL_LANGUAGE_CODE, 'page'));
    define('ID_LIST_MEDIA', ! empty($lp['app_listing_page_media'])
        ? getPostTranslatedID($lp['app_listing_page_media']->ID, ICL_LANGUAGE_CODE, 'page') : getPostTranslatedID(273, ICL_LANGUAGE_CODE, 'page'));
    
    $event_default_slug='';
    if (function_exists('get_field')) {
        $event_default_slug = get_field('app_event_type_default_slug', 'option');
    }
    define('EVENT_TYPE_DEFAULT_SLUG', ! empty($event_default_slug) ? $event_default_slug->slug : DEFAULT_SLUG_EVENT_TYPE);

    /**
     * Contact Constant
     */
    $default_email = 'bocatest@yahoo.com';
    if (function_exists('get_field')) {
        $email_form_contact = get_field('form_contact_email', 'option');
        $email_notification_stock = get_field('notification_dotation_stock', 'option');
    }
    define('APP_EMAIL_FORM_CONTACT', ! empty($email_form_contact) ? $email_form_contact : 'bocatest@yahoo.com');
    define('APP_EMAIL_NOTIFICATION_STOCK', ! empty($email_notification_stock) ? $email_notification_stock : 'bocatest@yahoo.com');
});

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
Filter::add('body_class', function ($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (! is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (! is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
});

/**
 * Disable jquery-migrate
 */
Action::add('wp_default_scripts', function ($scripts) {
    if (! is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
});

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
Action::add('wp_head', function () {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="'.esc_url(get_bloginfo('pingback_url')).'">';
    }
});

/**
 * Page loader
 */
Action::add('wp_footer', function () {
    ?>
    <script defer type="text/javascript">
        var opacity = 0;
        var intervalID = 0;

        fadeout();

        function fadeout() {
            intervalID = setInterval(hide, 50);
        }

        function hide() {
            var body = document.querySelector('body > #loader');

            if (body != null) {
                opacity = Number(window.getComputedStyle(body).getPropertyValue("opacity"));

                if (opacity > 0) {
                    opacity = opacity - 0.1;
                    body.style.opacity = opacity
                } else {
                    body.classList.add('loaded');
                    body.remove();
                    clearInterval(intervalID);
                }
            }
        }
    </script>
    <?php
});

/**
 * Add recaptcha v3 api
 */
Action::add('wp_head', function () {
    echo '<script defer src="https://www.google.com/recaptcha/api.js?render=6LfQa9wUAAAAAKK_GeBWAUslrh2PB9O7wMpU7xN2"></script>';
});

/**
 * Add recaptcha v3
 */
Action::add('wp_head', function () {

    $route = Route::currentRouteName();
    $action = str_replace(['-', '_', '&', '#'], '', $route);
    echo "<script defer>
        setTimeout(function(){
            grecaptcha.ready(function () {
                grecaptcha.execute('6LfQa9wUAAAAAKK_GeBWAUslrh2PB9O7wMpU7xN2', { action: '".$action."' }).then(function (token) {
                    var cmrsRecaptchaResponse = document.getElementById('recaptchaResponse');
                    if (cmrsRecaptchaResponse) {
                        cmrsRecaptchaResponse.value = token;
                    }
                });
            });
        }, 8000);
    </script>";

});

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
Action::add('after_setup_theme', function () {
    $GLOBALS['content_width'] = 640;
}, 0);

/**
 * ADD FAVICON ADMIN
 */
function add_favicon()
{
    $favicon_url = get_stylesheet_directory_uri().'/dist/favicon.ico';
    echo '<link rel="shortcut icon" href="'.$favicon_url.'" />';
}

add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');

/**
 * Remove unused styles
 */
Action::add('wp_enqueue_scripts', function () {
//    wp_dequeue_style('login-with-ajax');
//    wp_dequeue_style('wp-block-library');
//    wp_dequeue_style('wc-block-style');
    wp_dequeue_style('yith-wcwl-font-awesome');
    wp_dequeue_style('jquery-selectBox');
    wp_dequeue_style('woocommerce_prettyPhoto_css');
    wp_dequeue_style('sitepress-style');
    wp_dequeue_style('wpml-legacy-horizontal-list-0');

    $param = request()->get('elementor-preview');
    if (! is_admin() && ! is_customize_preview() && empty($param)
        &&
        (is_product() || is_shop() || is_product_category() || is_product_tag() || Route::currentRouteName() == 'search-product')) {
        wp_deregister_script('jquery-ui-core');
        wp_dequeue_script('jquery-ui-core');
        wp_deregister_script('jquery-ui-widget');
        wp_dequeue_script('jquery-ui-widget');
        wp_deregister_script('jquery-ui-mouse');
        wp_dequeue_script('jquery-ui-mouse');
        wp_deregister_script('jquery-ui-draggable');
        wp_dequeue_script('jquery-ui-draggable');
        wp_deregister_script('jquery-ui-position');
        wp_dequeue_script('jquery-ui-position');
    }

    if (! is_cart() && ! is_checkout() && ! is_account_page() && ! is_wc_endpoint_url()) {

        ## Dequeue WooCommerce styles
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-smallscreen');

        ## Dequeue WooCommerce scripts
        wp_dequeue_script('wc-cart-fragments');
        wp_dequeue_script('woocommerce');
        wp_dequeue_script('wc-add-to-cart');

        wp_deregister_script('js-cookie');
        wp_dequeue_script('js-cookie');
    }
}, 11);

/**
 * Header menu function
 */
Action::add('wp_site_header_menu', function () {
    $args = [
        'menu_class' => 'block-body uk-navbar-nav',
        'menu_id' => false,
        'container' => 'ul',
        'container_class' => 'block-body uk-navbar-nav',
        'container_id' => false,
        'fallback_cb' => '',
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'echo' => '',
        'depth' => 0,
        'walker' => new Theme\Providers\Dy_Walker_Nav_Menu_Header(),
        'theme_location' => 'header-menu',
        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
        'item_spacing' => '',
    ];

    echo wp_nav_menu($args);
});

/**
 * Footer menu function
 */
Action::add('wp_site_footer_menu_1', function () {
    $args = [
        'menu_class' => 'block-body',
        'menu_id' => false,
        'container' => 'ul',
        'container_class' => 'block-body',
        'container_id' => false,
        'fallback_cb' => '',
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'echo' => '',
        'depth' => 0,
        'walker' => new Theme\Providers\Dy_Walker_Nav_Menu_Footer(),
        'theme_location' => 'footer-menu-1',
        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
        'item_spacing' => '',
    ];

    echo wp_nav_menu($args);
});

/**
 * Footer menu function 2
 */
Action::add('wp_site_footer_menu_2', function () {
    $args = [
        'menu_class' => 'uk-grid-small uk-child-width-1-5',
        'menu_id' => false,
        'container' => 'ul',
        'container_class' => 'uk-grid-small uk-child-width-1-5',
        'container_id' => false,
        'fallback_cb' => '',
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'echo' => '',
        'depth' => 0,
        'walker' => new Theme\Providers\Dy_Walker_Nav_Menu_Footer_2(),
        'theme_location' => 'footer-menu-2',
        'items_wrap' => '<div class="%2$s" data-uk-grid>%3$s</div>',
        'item_spacing' => '',
    ];

    echo wp_nav_menu($args);
});

/**
 * Breadcrumb function
 */
Action::add('breadcrumb_navigation', function () {
    $post_parent_id = null;
    $home_title = __('Accueil', THEME_TD);
    $post_type = get_post_type();
    $post_id = get_the_ID();

    $post_parent_id = wp_get_post_parent_id($post_id);

    $breadcrumb = '<div id="primary" class="section container-fluid">
					  <div class="section-body inner">
						<div class="row">
						  <div class="col-sm-10 col-sm-offset-1">
							<div class="block block-section__breadcrumb uk">
							  <div class="block-content">
								<ul class="block-body uk-breadcrumb">
                    <li><a href="'.home_url().'" title="'.$home_title.'">'.$home_title.'</a></li>';

    $closingbreadcrumb = '</ul></div></div></div></div></div></div>';
    $taxclosingbreadcrumb = $closingbreadcrumb;
//	$taxclosingbreadcrumb = '</ul></div></div>';

    if (is_archive() && ! is_tax() && ! is_category() && ! is_tag()) {

        //echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';

    } else {
        if (is_archive() && is_tax() && ! is_category() && ! is_tag()) {

            $cat = get_queried_object();
            $cat_id = $cat->term_id;
            $cat_title = $cat->name;
            $cat_taxonomy = $cat->taxonomy;
            //$cat_desc = category_description( $cat_id );
            $cat_link = get_term_link($cat_id);

            if ($cat_taxonomy == 'media_category') {
                $breadcrumb .= '<li><a href="'.get_permalink(ID_LIST_MEDIA).'" title="'.__('Téléchargement', THEME_TD).'">'.__('Téléchargement', THEME_TD)
                    .'</a></li>';
            } else {
                $breadcrumb .= '<li><a href="#" title="'.__('Catégorie', THEME_TD).'">'.__('Catégorie', THEME_TD).'</a></li>';
            }
            $breadcrumb .= '<li><span title="'.$cat_title.'">'.$cat_title.'</span></li>';
            $breadcrumb .= $taxclosingbreadcrumb;
        } else {
            if (is_category() && ! is_tag() && ! is_tax()) {
                $cat_id = get_query_var('cat');
                $cat = get_category($cat_id);
                $cat_title = $cat->name;
                //$cat_desc = category_description( $cat_id );
                $cat_link = get_term_link($cat_id);

                $breadcrumb .= '<li><a href="'.get_permalink(ID_LIST_POST).'" title="'.__('Blog', THEME_TD).'"><span>'.__('Blog', THEME_TD).'</span></a></li>';
                $breadcrumb .= '<li><span title="'.$cat_title.'">'.$cat_title.'</span></li>';

                $breadcrumb .= $taxclosingbreadcrumb;
            } elseif (is_tag() && ! is_tax()) {
                $tag_id = get_query_var("tag_id");
                $tag = get_tag(get_term($tag_id));
                $tag_title = $tag->name;
                //$cat_desc = category_description( $cat_id );
                //$tag_link = get_term_link(pll_get_term($tag_id));

                $breadcrumb .= '<li><a href="'.get_permalink(ID_LIST_POST).'"><span title="'.__('Blog', THEME_TD).'">'.__('Blog', THEME_TD).'</span></a></li>';
                $breadcrumb .= '<li title="'.$tag_title.'"><span>'.$tag_title.'</span></li>';

                $breadcrumb .= $taxclosingbreadcrumb;
            } else {
                if (is_single()) {
                    if ($post_type === 'post') {
                        $cat = getPrimaryTaxTerm('category', true, $post_id);

                        $breadcrumb .= '<li><a href="'.get_permalink(ID_LIST_POST).'" title="'.__('Blog', THEME_TD).'">'.__('Blog', THEME_TD).'</a></li>';
                        if (! empty($cat)) {
                            $breadcrumb .= '<li><a href="'.get_term_link($cat->term_id).'" title="'.$cat->name.'">'.$cat->name.'</a></li>';
                        }
                        $breadcrumb .= '<li><span>'.get_the_title($post_id).'</span></li>';
                        $breadcrumb .= $closingbreadcrumb;
                    } elseif ($post_type === 'salon') {
                        $cat = getPrimaryTaxTerm('salon_cat', true, $post_id);

                        $breadcrumb .= '<li><a href="'.get_permalink(ID_LIST_SALON).'" title="Agenda">Agenda</a></li>';
                        if (! empty($cat)) {
                            $breadcrumb .= '<li><a href="'.get_term_link($cat->term_id).'" title="'.$cat->name.'">'.$cat->name.'</a></li>';
                        }

                        $breadcrumb .= '<li><span>'.get_the_title($post_id).'</span></li>';
                        $breadcrumb .= $closingbreadcrumb;
                    }
                } elseif (is_page()) {
                    $p_ancestor = get_post_ancestors($post_id);
                    if (is_array($p_ancestor) && count($p_ancestor) > 0) {
                        $p_ancestor = array_reverse($p_ancestor);
                        foreach ($p_ancestor as $p_id) {
                            $sp_ancestor = get_post($p_id);
                            if (! empty($sp_ancestor)) {
                                $breadcrumb .= '<li><a href="'.get_permalink($p_id).'" title="'.get_the_title($p_id).'">'.get_the_title($p_id).'</a></li>';
                            } else {
                                $breadcrumb .= '<li title="'.get_the_title($p_id).'">'.get_the_title($p_id).'</li>';
                            }
                        }
                    }
                    $breadcrumb .= '<li><span>'.get_the_title($post_id).'</span></li>';
                    $breadcrumb .= $closingbreadcrumb;
                }
            }
        }
    }

    echo $breadcrumb;
});

/*
 * Init gutenberg blocs
 * */
Action::add('init', function () {
    $post_type_object = get_post_type_object('post');
    $post_type_object->template = array(
        array('core/heading', array()),
        array('core/paragraph', array()),
        array('core/list', array()),
    );

    $post_type_object = get_post_type_object('page');
    $post_type_object->template = array(
        array('custom-gutenberg-elements/cge-content-breadcrumbs', array()),
        array('custom-gutenberg-elements/cge-full-page-heading', array()),
        array('custom-gutenberg-elements/cge-full-page-banner', array()),
        array('custom-gutenberg-elements/cge-full-layout-content', array()),
    );
});

/**
 * WPML Language switcher
 */
Action::add('custom_wpml_language_switcher', function () {
    $datas = [];
    $datas['languages'] = icl_get_languages('skip_missing=0&orderby=code');
    $datas['post_type'] = '';
    $object = get_queried_object();
    if ($object instanceof WP_Post && $object->post_type == 'post') {
        $datas['post_type'] = $object->post_type;
    }
    $datas['url_parameter'] = '';
    $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $separate_link = explode('?', $actual_link);
    if (isset($separate_link[1]) && ! empty($separate_link[1])) {
        $datas['url_parameter'] = '?'.$separate_link[1];
    }

    if (Route::currentRouteName() == 'showroom-template' && ! empty($datas['languages'])) {
        $new_datas = [];
        foreach ($datas['languages'] as $key => $lang) {
            $lang['url'] = showroomGetUrl(getEventSalonSlugInSession(), $lang['code']);
            $new_datas[$key] = $lang;
        }
        $datas['languages'] = $new_datas;
    }

    echo view('common.lang_switcher', $datas)->render();
});

/**
 * Custom import product page
 */
Action::add('admin_menu', function () {
    function myplguin_admin_page()
    {
        $args = [];

        ob_start();
        echo View::make('admin_pages.import-product', $args)->render();
        $object = ob_get_contents();
        ob_end_clean();

        echo $object;
    }

    add_menu_page('Import Produits template', __('Import Produits', THEME_TD), 'manage_options', 'import-products-camerus.php', 'myplguin_admin_page',
        'dashicons-portfolio', 57);
});

/**
 * Custom Stat download page
 */
Action::add('admin_menu', function () {
    function stat_plugin_admin_page()
    {
        $args = [];

        $args['page'] = request()->get('page');

        $search_date = date('Y-m', time());
        $input_date = request()->get('stat_date');
        if (! empty($input_date)) {
            $search_date = $input_date;
        }
        $stats = DownloadStats::where('date', 'like', '%'.$search_date.'%')->orderBy('count', 'DESC')->get()->toArray();
        $all_stats = [];
        if (! empty($stats)) {
            foreach ($stats as $stat) {
                if (array_key_exists($stat['slug'], $all_stats)) {
                    $all_stats[$stat['slug']]['count'] += $stat['count'];
                } else {
                    $all_stats[$stat['slug']] = $stat;
                }
            }
        }
        $args['stats'] = $all_stats;

        ob_start();
        echo View::make('admin_pages.stat-download', $args)->render();
        $object = ob_get_contents();
        ob_end_clean();

        echo $object;
    }

    add_menu_page('Stats Téléchargement', __('Stats Téléchargement', THEME_TD), 'manage_options', 'stat-download-camerus.php', 'stat_plugin_admin_page',
        'dashicons-list-view', 57);
});

/**
 * admin-ajax.php
 */
Action::add('admin_footer', function () {
    echo '<script>/* <![CDATA[ */ var cmrs_admin_ajax = "'.admin_url('admin-ajax.php').'";  /* ]]> */ </script>';
});

/**
 * Add Box manage XML Rent in Order
 */
Metabox::make('rent_xml_order_manager', 'shop_order')
    ->setTitle(_x('Gestion du XML Rent+', 'metabox', THEME_TD))
    ->setCallback('App\Metabox\RentXmlManager@index')
    ->set();

/**
 * ² * Save Box Rent+ Order XML Backoffice
 */
Action::add('save_post', function ($post_id, $post) {
    if ($post->post_type == 'shop_order' && is_admin()) {
        if (array_key_exists('rent-order-xml', $_POST)) {
            $rentOrderManager = new RentOrderManager;
            $content = trim(stripslashes($_POST['rent-order-xml']));
            $content = str_replace('&', 'et', $content);
            $rentOrderManager->saveXmlFile($post_id, $content);

            if (array_key_exists('rent-order-xml-send', $_POST) && ! empty($_POST['rent-order-xml-send'])) {
                $rentOrderManager->sendRequest($content);
            }
        }
    }
}, 10, 2);

/**
 * Save Box Rent+ Order XML Data Backoffice
 */
Action::add('save_post', function ($post_id, $post) {
    if ($post->post_type == 'shop_order' && is_admin()) {
        if (! isset($_POST['rent-order-xml-send'])) {
            $rentOrderManager = new RentOrderManager;
            $order_id = $post_id;
            $order = wc_get_order($order_id);
            $data = $rentOrderManager->getXmlData($order);
            $content = $rentOrderManager->getXmlTemplateContent($data);
            $rentOrderManager->saveXmlFile($order_id, $content);
        }
    }
}, 10, 2);

/**
 * Add Box Custom UGS/SKU product
 */
Metabox::make('product_custom_sku', 'product')
    ->setTitle(_x('Références', 'metabox', THEME_TD))
    ->setPriority('high')
    ->setCallback('App\Metabox\ProductDataManager@index')
    ->set();

/**
 * Save Box Rent+ Order XML
 */
Action::add('save_post', function ($post_id, $post) {
    if ($post->post_type == 'product') {
        if (array_key_exists('product-custom-sku', $_POST)) {
            try {
                $product = wc_get_product($post_id);
                $product->set_sku(trim((string)$_POST['product-custom-sku']));
                $product->save();
            } catch (Exception $e) {
                return false;
            }
        }
    }
}, 10, 2);

/**
 * Post preview redirect
 */
Action::add('template_redirect', function () {

    $id = get_the_ID();
    $type = get_post_type($id);

    if ($type == 'post') {
        $preview = request()->get('preview');
        $status = get_post_status($id);

        if ($status != 'publish' && $preview != 'true') {
            wp_redirect(home_url());
            exit;
        }
        if ($preview == 'true' && ! is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }
    }
});

/**
 * Save empty cache POST
 */
Action::add('save_post', function ($post_id, $post) {
    if ($post->post_type == 'post' && class_exists('WPO_Page_Cache')) {
        cmsr_delete_post_wpo_cache(get_option('page_on_front'), 'page', false);
        cmsr_delete_post_wpo_cache(ID_LIST_POST, 'page');
        $cats = get_categories();
        if (! empty($cats)) {
            foreach ($cats as $cat) {
                cmsr_delete_term_wpo_cache($cat->term_id, 'category');
            }
        }
    }
}, 10, 2);

/**
 * Save empty cache SALON
 */
Action::add('save_post', function ($post_id, $post) {
    if ($post->post_type == 'salon' && class_exists('WPO_Page_Cache')) {
        cmsr_delete_post_wpo_cache(get_option('page_on_front'), 'page', false);
        cmsr_delete_post_wpo_cache(ID_LIST_SALON, 'page', false);

        $ids = cmrs_get_all_translated_ids($post_id, 'salon');
        if (! empty($ids)) {
            foreach ($ids as $lang => $id) {
                $salon = get_post($id);
                if (! empty($salon)) {
                    $url = showroomGetUrl($salon->post_name, $lang);
                    WPO_Page_Cache::delete_cache_by_url($url, true);
                }
            }
        }

//        cmrsGenerateNewAgendaPdf();
    }
}, 10, 2);

/**
 * Save empty cache PRODUCT
 */
Action::add('save_post', function ($post_id, $post) {
    if ($post->post_type == 'product' && class_exists('WPO_Page_Cache')) {
        cmsr_delete_post_wpo_cache(wc_get_page_id('shop'), 'page', false);
        $cats = wp_get_post_terms($post_id, 'product_cat');
        if (! empty($cats)) {
            foreach ($cats as $cat) {
                cmsr_delete_term_wpo_cache($cat->term_id, 'product_cat');
            }
        }

        $langs = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
        if (! empty($langs)) {
            foreach ($langs as $lang => $value) {
                $url = showroomGetUrl('', $lang);
                WPO_Page_Cache::delete_cache_by_url($url, true);
            }
        }
    }
}, 10, 2);

Action::add('wp_head', function ()
{
    if (is_home() || is_front_page()) {
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

        echo '<link rel="preload" href="'.esc_url($image_url).'" as="image" fetchpriority="high">';
        echo '<link rel="preload" href="'.esc_url($image_url).'.webp" as="image" fetchpriority="high">';
    }
});