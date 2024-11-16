<?php

use App\Hooks\Product;
use App\Hooks\Salon;

function getCategoryZipPath($slug)
{
    global $sitepress;

    $cat_slug = esc_attr(wc_clean($slug));

    $files = [];
    if (!empty($cat_slug)) {
        $term = get_term_by('slug', $cat_slug, 'product_cat');
        if (!empty($term)) {
            $lang = ICL_LANGUAGE_CODE;
            $sitepress->switch_lang($lang);

            $product_args = [
                'order' => 'ASC',
                'orderby' => 'title',
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

            if ($query->have_posts()) {
                $products = $query->posts;
                foreach ($products as $product) {
                    $product_3d_file = get_field('product_3d_file', $product->ID);
                    if (!empty($product_3d_file)) {
                        $sku = get_post_meta($product->ID, '_sku', true);
                        $file_path = get_attached_file($product_3d_file['ID']);
                        $file_url = $product_3d_file['url'];
                        if (file_exists($file_path)) {
                            $files[] = [
                                'img_url' => get_the_post_thumbnail_url($product->ID, 'thumbnail'),
                                'sku' => str_replace('-GB', '', $sku),
                                'name' => $product->post_title . ' ' . str_replace('-GB', '', $sku),
                                'path' => $file_path,
                                'zip_url' => $file_url,
                            ];
                        }
                    }
                }
            }
        }
    }

    return $files;
}

function cmrsGenerateNewAgendaPdf()
{
    $salon_args = [];
    $salons = Salon::getSalon($salon_args);

    setDateTimeLocalFormat();

    $args = [];
    $args['salons'] = $salons;
    $pdf_uri = generateHtmlToPdf($args, 'Agenda-' . SITE_MAIN_SYS_NAME, 'agenda', 'pdf.agenda-pdf');
//    $file_name = basename($pdf_uri);
//    $pdf_file_path = wp_get_upload_dir()['basedir'] . '/agenda/' . $file_name;

    return $pdf_uri;
}