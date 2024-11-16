<?php

namespace App\Metabox;


use App\Hooks\Product;
use App\StockSalDot;

class StockSalonManager
{
    public function index($args)
    {
        $view_args = [];
        $all_types = [];
        $salon_id = get_the_ID();
        $salon_ref = get_field('salon_id', $salon_id);
        $types = Product::getDotationTypes();
        $selected_types = get_post_meta(get_the_ID(), '_ssm_types', true);

        if (!empty($types) && is_array($types)) {
            foreach ($types as $type) {
                $type_dotations = [];
                $all_dotation = [];
                $type->is_checked = false;

                if (!empty($selected_types) && in_array($type->slug, $selected_types)) {
                    $type->is_checked = true;
                }

                if (!empty($salon_ref) && $type->is_checked) {
                    $dotations = get_posts([
                        'post_type'   => 'product',
                        'numberposts' => -1,
                        'order'       => 'ASC',
                        'orderby'     => 'title',
                        'tax_query'   => [
                            'relation' => 'AND',
                            [
                                'taxonomy' => 'product_type',
                                'field'    => 'slug',
                                'terms'    => 'dotation',
                            ],
                            [
                                'taxonomy' => 'product_dotation_type',
                                'field'    => 'slug',
                                'terms'    => $type->slug
                            ]
                        ]
                    ]);

                    if (!empty($dotations) && is_array($dotations)) {
                        foreach ($dotations as $dotation) {
                            $dotation_ref = get_post_meta($dotation->ID, '_sku', true);
                            $stock = StockSalDot::where('id_salon', $salon_ref)
                                ->where('id_dotation', $dotation_ref)
                                ->first();
                            if ($stock == null) {
                                $stock = 0;
                            } else {
                                $stock = $stock->stock;
                            }

                            $dotation->dotation_ref = $dotation_ref;
                            $dotation->dotation_quantity = $stock;

                            $p_max = (int) get_field('dotation_surface_max', $dotation->ID);
                            $p_min = (int) get_field('dotation_surface_min', $dotation->ID);
                            $d_key = (float) $p_min.'.'.$p_max;

                            $type_dotations[$d_key][] = $dotation;
                        }
                    }
                }

                if (!empty($type_dotations)) {
                    krsort($type_dotations);
                    foreach ($type_dotations as $typeDotation) {
                        $all_dotation = array_merge($all_dotation, $typeDotation);
                    }
                }

                $type->dotations = $all_dotation;
                $all_types[] = $type;
            }
        }

        $view_args['types'] = $all_types;
        $view_args['salon_ref'] = $salon_ref;

        return view('metabox.stock-salon', $view_args);
    }
}