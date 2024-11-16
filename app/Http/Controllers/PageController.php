<?php

namespace App\Http\Controllers;

use App\Forms\SalonFilterForm;
use App\Hooks\Product;
use App\Hooks\Salon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Themosis\Core\Forms\FormHelper;
use Themosis\Core\Validation\ValidatesRequests;
use WP_User;
use WPO_Page_Cache;
use ZipStream\Exception\OverflowException;

class PageController extends BaseController
{
    use FormHelper, ValidatesRequests;

    /**
     * Get post SEO Meta
     *
     * @param $id
     *
     * @return mixed
     */
    protected function getPostMetas($id)
    {
        $metatitle = get_post_meta($id, '_yoast_wpseo_title', true);
        $metadesc = get_post_meta($id, '_yoast_wpseo_metadesc', true);

        if (empty($metatitle)) {
            $metatitle = get_the_title($id);
        }
        if (empty($metadesc)) {
            $metadesc = get_bloginfo('description');
        }
        $args['metatitle'] = $metatitle;
        $args['metadesc'] = $metadesc;

        return $args;
    }

    /**
     * Get Post Thumbnail
     *
     * @param $id
     *
     * @return false|string
     */
    protected function getPostThumbnail($id)
    {
        $post_thumb = get_the_post_thumbnail_url($id);

        if (empty($post_thumb)) {
            return get_stylesheet_directory_uri() . '/dist/images/header-logo.svg';
        }

        return $post_thumb;
    }

    /**
     * Query get Salon list
     *
     * @return array
     */
    protected function getSalonList()
    {
        $post_args = [
            'numberposts' => 5,
        ];

        $salons = Salon::getSalon($post_args);

        return $salons;
    }

    /**
     * indexHome Controller
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexHome($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);
        $home_video ='';
        if(function_exists('get_field')){
            $home_video = get_field('home_video', $page_id);
       
//        $args['home_video'] = getHomeVideoEmbed($home_video);
            $args['home_video'] = $home_video;
            $args['home_png_img'] = get_field('home_png_img', $page_id);
        }
        $posts = [];
        $sticky_posts = [];
        $sticky = get_option('sticky_posts');
        $sticky_number = 0;
        if (!empty($sticky) && is_array($sticky)) {
            $sticky_number = count($sticky);
            foreach ($sticky as $key => $stick) {
                if ($key < 3) {
                    $sticky_posts[] = get_post($stick);
                }
            }
        }

        if ($sticky_number < 3) {
            $numberposts = 3 - $sticky_number;
            $post_args = [
                'numberposts' => $numberposts,
                'post_status' => 'publish',
                'order' => 'DESC',
                'orderby' => 'date',
                'suppress_filters' => false,
                'post__not_in' => $sticky,
                'ignore_sticky_posts' => 1
            ];

            $posts = get_posts($post_args);
        }

        wp_reset_query();

        $posts = array_merge($sticky_posts, $posts);

        $args['f_posts'] = $posts;
        $post_args = [
            'numberposts' => -1,
        ];

        $args['salons'] = Salon::getSalon($post_args);
        $args['months'] = Salon::getAgendaMonth();
        $args['cities'] = Salon::getAgendaCity();

//		dd($posts);

        return view('pages.home', $args);
    }

    /**
     * indexPage Controller
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexPage($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);

        //td($arg);

        return view('pages.default', $args);
    }

    /**
     * indexBlog controller
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexBlog($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);

        $post_args = [
            'numberposts' => -1,
            'order' => 'DESC',
            'orderby' => 'date',
            'suppress_filters' => false
        ];

        $posts = get_posts($post_args);

        wp_reset_query();

        $args['posts'] = $posts;
        $args['post_count'] = count($posts);

        return view('blog.archive', $args);
    }

    /**
     * indexAgenda controller
     *
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexAgenda($page, $query)
    {
        $args = [];
        setDateTimeLocalFormat();

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);

        $post_args = [
            'numberposts' => -1,
            'suppress_filters' => false
        ];
        $city = Request::input(SLUG_EVENT_CITY_QUERY);
        if (!empty($city)) {
            $post_args['tax_query'][] = [
                'taxonomy' => 'salon_city',
                'field' => 'slug',
                'terms' => $city
            ];
        }
        $args['salons'] = Salon::getSalon($post_args);
        $args['months'] = Salon::getAgendaMonth();
        $args['cities'] = Salon::getAgendaCity();

        return view('pages.agenda', $args);
    }

    /**
     * Download Agenda PDF
     *
     * @param $slug
     *
     * @return false|resource|string
     */
    public function indexAgendaPdf($slug = '')
    {
        $salon_args = [];
        $salons = Salon::getSalon($salon_args);

        setDateTimeLocalFormat();

        $args = [];
        $args['salons'] = $salons;
        $pdf_uri = generateHtmlToPdf($args, 'Agenda-' . SITE_MAIN_SYS_NAME, 'agenda', 'pdf.agenda-pdf');
        $file_name = basename($pdf_uri);
        $pdf_file_path = wp_get_upload_dir()['basedir'] . '/agenda/' . $file_name;
        if (!empty($pdf_uri)) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-type: application/pdf", true, 200);
            header('Content-Disposition: attachment; filename=' . $file_name);
            header("Content-Type: application/force-download");
            header('Content-Description: ' . SITE_MAIN_SYS_NAME . ' Agenda PDF File Download');
            header('Content-Transfer-Encoding: binary');

            flush(); // This doesn't really matter.


            $fp = file_get_contents($pdf_file_path);
            if (!$fp) {
                $fp = fopen($pdf_file_path, "r");
                while (!feof($fp)) {
                    echo fread($fp, 65536);
                    flush(); // This is essential for large downloads
                }
                fclose($fp);
            }

            return $fp;

        } else {
            die('Error - can not open file.');
        }
        die();
    }

    /**
     * @param $page
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexEmpty($page, $query)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);

        return view('pages.empty', $args);
    }

    /**
     * @param $page
     *
     * @return \Illuminate\Contracts\View\Factory|Factory|View
     */
    public function indexDownload($page)
    {
        global $wp;

        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);
        $args['current_url'] = home_url($wp->request);
        $args['page_title'] = __('Téléchargement', THEME_TD);

        /**
         * Aside taxonomy menu
         */
        $args['media_category'] = get_terms([
            'taxonomy' => SLUG_TAX_MEDIA_CATEGORY,
            'hide_empty' => false
        ]);

        $att_args = [
            'post_type' => 'attachment',
            'numberposts' => -1,
            'order' => 'ASC',
            'orderby' => 'title'
        ];

        /**
         * Filter media list
         */
        $order = Request::input('order');
        $orderby = Request::input('orderby');

        if (!empty($order)) {
            $att_args['order'] = strtoupper($order);
        }
        if (!empty($orderby)) {
            $att_args['orderby'] = strtolower($orderby);
        }

        $queried_obj = get_queried_object();

        /**
         * Query grouped by taxonomy
         */
        if (!empty($queried_obj->taxonomy) && $queried_obj->taxonomy === SLUG_TAX_MEDIA_CATEGORY) {
            $term_query = [$queried_obj->slug];
            $args['page_title'] = __('Téléchargement', THEME_TD) . ' : ' . ucfirst($queried_obj->name);
        } else {
            $args['page_title'] = __('Accueil Téléchargement', THEME_TD);
            $term_query = ['fichiers-3d', 'fiches-produits'];
        }

        $q_medias = [];
        if (!empty($term_query) && is_array($term_query)) {
//            dd($term_query);
            foreach ($term_query as $tq) {
                $term = get_term_by('slug', $tq, SLUG_TAX_MEDIA_CATEGORY);
                if (!empty($term)) {
                    $att_args['tax_query'][0] = [
                        'taxonomy' => SLUG_TAX_MEDIA_CATEGORY,
                        'field' => 'slug',
                        'terms' => [$tq],
                        'operator' => 'IN'
                    ];
                    $type = 'post';

                    if ($tq === 'agenda-des-salons' || $tq === 'agendadessdalons' || $tq === 'calendarevents') {
                        $q_medias[] = [
                            'type' => 'url',
                            'term' => $term,
                            'medias_list' => ['url' => home_url('/agenda-pdf')]
                        ];
                    } elseif ($tq === 'fichiers-3d' || $tq === 'modeles3d' || $tq === '3dmodels') {
                        $type = 'zip-file';
                        $cat_args = [
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'order' => 'ASC',
                            'orderby' => 'title'
                        ];
                        if (!empty($order)) {
                            $cat_args['order'] = strtoupper($order);
                        }
                        if (!empty($orderby)) {
                            $cat_args['orderby'] = strtolower($orderby);
                        }
                        $product_categories = [];
                        $_product_categories = get_terms($cat_args);
                        if (!empty($_product_categories) && is_array($_product_categories)) {
                            foreach ($_product_categories as $productCategory) {
                                $term_id = getPostTranslatedID($productCategory->term_id, ICL_LANGUAGE_CODE, 'product_cat');
                                if(function_exists('get_field')){
                                    $view_type = get_field('media_cat_view_download', 'product_cat_' . $term_id);
                                }
                                if (empty($view_type)) {
                                    $product_categories[] = $productCategory;
                                }
                            }
                        }
                        $the_term = get_term_by('slug', $tq, 'media_category');

                        $q_medias[] = [
                            'type' => $type,
                            'term' => $the_term,
                            'medias_list' => $product_categories
                        ];
                    } else {
                        $q_medias[] = [
                            'type' => $type,
                            'term' => $term,
                            'medias_list' => get_posts($att_args)
                        ];
                    }
                    wp_reset_query();
                }
            }
        }

        $args['medias'] = $q_medias;

        return view('pages.download', $args);
    }

    public function index3DZipFile($slug)
    {
        global $sitepress;
        if (!empty($slug)) {
            $cat_slug = esc_attr($slug);

            if (!empty($cat_slug)) {
                $term = get_term_by('slug', $cat_slug, 'product_cat');
                if (!empty($term)) {
                    $lang = ICL_LANGUAGE_CODE;
                    $sitepress->switch_lang($lang);

                    $product_args = [
                        'tax_query' => [
                            [
                                'taxonomy' => 'product_cat',
                                'field' => 'slug',
                                'terms' => $cat_slug,
                                'include_children' => true,
                                'operator' => 'IN'
                            ]
                        ]
                    ];
                    $query = Product::query($product_args);

                    $files = [];
                    if ($query->have_posts()) {
                        $products = $query->posts;
                        foreach ($products as $product) {
                            if(function_exists('get_field')){
                                $product_3d_file = get_field('product_3d_file', $product->ID);
                            }
                            if (!empty($product_3d_file)) {
                                $sku = get_post_meta($product->ID, '_sku', true);
                                $file_path = get_attached_file($product_3d_file['ID']);
                                if (file_exists($file_path)) {
                                    $files[] = [
                                        'name' => $product->post_title . '-' . str_replace('-GB', '', $sku),
                                        'path' => $file_path
                                    ];
                                }
                            }
                        }
                    }

                    try {
                        custom_create_zip_file_download($term->name . '-' . date('Y-m-d') . '.zip', $files);
                    } catch (OverflowException $e) {
                        echo $e;
                    }
                }
            }
        }
    }

    public function indexProCustomer($page)
    {
        $args = [];

        $page_id = $page->ID;

        $args['ID'] = $page_id;
        $args['page'] = $page;

        $args = array_merge($this->getPostMetas($page_id), $args);
        $args['post_thumb'] = $this->getPostThumbnail($page_id);

        /**
         * Form manager
         */
        $args['user_name'] = null;
        $args['user_email'] = null;
        $args['user_error'] = false;
        $args['user_recaptcha_error'] = false;
        $formData = request()->all();

        if (!empty($formData) && is_email($formData['billing_email'])) {
            $recaptcha = checkRecaptchaV3();

            if (!empty($recaptcha)) {
                $userdata = array(
                    'user_login' => $formData['billing_email'],
                    'user_email' => $formData['billing_email'],
                    'user_pass' => 'camerus-' . $formData['billing_email'],
                    'display_name' => sanitize_text_field($formData['billing_first_name'] . ' ' . $formData['billing_last_name']),
                    'first_name' => sanitize_text_field($formData['billing_first_name']),
                    'last_name' => sanitize_text_field($formData['billing_last_name']),
                    'role' => 'procustomer',
                );

                $user_id = wp_insert_user($userdata);

                if (!is_wp_error($user_id)) {
                    update_user_meta($user_id, 'wp-approve-user', false);
                    update_user_meta($user_id, 'billing_gender', sanitize_text_field($formData['billing_gender']));
                    update_user_meta($user_id, 'billing_company', sanitize_text_field($formData['billing_company']));
                    update_user_meta($user_id, 'billing_address_1', sanitize_text_field($formData['billing_address_1']));
                    update_user_meta($user_id, 'billing_postcode', (int)$formData['billing_postcode']);
                    update_user_meta($user_id, 'billing_phone', sanitize_text_field($formData['billing_phone']));
                    update_user_meta($user_id, 'billing_num_tva', sanitize_text_field($formData['billing_num_tva']));

                    $args['user_name'] = $formData['billing_first_name'] . ' ' . $formData['billing_last_name'];
                    $args['user_email'] = $formData['billing_email'];

                    $data = [];
                    $data['email_type'] = 'procustomer-sign';
                    $to = $formData['billing_email'];
                    $subject = __("Demande d'accès à nos tarifs événements", THEME_TD);
                    sendEmailType($to, $subject, $data);
                    sendShopManagersNotification($user_id);
                } else {
                    $args['user_error'] = true;
                }
            } else {
                $args['user_recaptcha_error'] = true;
            }
        }

//        dd($formData);

        return view('pages.pro-customer-sign-in', $args);
    }

    public function indexWpCronExec()
    {
        do_action('cmrs_empty_session_data');
        Session::flush();

        if (class_exists('WPO_Page_Cache')) {
            cmsr_delete_post_wpo_cache(get_option('page_on_front'), 'page', false);
            cmsr_delete_post_wpo_cache(ID_LIST_SALON, 'page', false);
            cmrsGenerateNewAgendaPdf();
        }

        return 'Exec Cron success';
    }
}