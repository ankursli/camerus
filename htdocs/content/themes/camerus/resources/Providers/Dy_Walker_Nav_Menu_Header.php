<?php

namespace Theme\Providers;

use Illuminate\Support\ServiceProvider;
use Themosis\Core\ThemeManager;
use Themosis\Support\Facades\Asset;
use Walker_Nav_Menu;

/**
 * Custom menu header
 * Class Dy_Walker_Nav_Menu_Header
 *
 * @package Theme\Providers
 */
class Dy_Walker_Nav_Menu_Header extends Walker_Nav_Menu
{

    public $count = 0;
    public $count2 = 0;

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $class_names = $value = $header_class = '';
        $dropdown_attr = $dropdown_class = '';

        $has_children = $args->walker->has_children;

        if ($has_children) {
            $dropdown_attr = '';
            $dropdown_class = 'is-parent has-children';
        }

        if ($depth === 1 && ($this->count === 0 || $this->count2 === 0)) {
            $header_class = "uk-nav-header";
        }

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-'.$item->ID;
        $classes[] = $dropdown_class;
        $classes[] = $header_class;
        $first_class = '';
        if ($item->object == 'product_cat' && $item->type == 'taxonomy') {
            $salon = getEventSalonObjectInSession();
            if (!empty($salon)) {
                $hidden_cat = get_field('salon_hide_cat', $salon->ID);
                $object_id = (int) $item->object_id;
                if (!empty($hidden_cat) && in_array($object_id, $hidden_cat)) {
                    $classes[] = 'hide';
                    $first_class = 'hide';
                }
            }
        }
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="'.esc_attr($class_names).'"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
        $id = $id ? ' id="'.esc_attr($id).'"' : '';

        if (in_array('separate', $classes, false)) {
            $this->count2 = 0;
            $output .= '</ul></div>';
            $output .= '<div><ul class="uk-nav uk-navbar-dropdown-nav">';
        } else {
            $output .= $indent.'<li'.$class_names.'>';
        }

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' '.$attr.'="'.$value.'"';
            }
        }

        if (in_array('separate', $classes, false)) {
            $item_output = '';
        } elseif ($depth === 1 && ($this->count === 0 || $this->count2 === 0)) {
            $this->count++;
            $this->count2++;
            $item_output = $args->before;
            $item_output .= '<span class="'.$first_class.'">';
            $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
            $item_output .= '</span>';
            $item_output .= $args->after;
        } else {
            $item_output = $args->before;
            $item_output .= '<a'.$attributes.' '.$dropdown_attr.' '.$class_names.'>';
            $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
        }

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        if (0 === $depth) {
            $output .= '<div class="uk-navbar-dropdown container-fluid"
					 data-uk-drop="boundary: #boundary; boundary-align: true; pos: bottom-justify;">
					<i class="icon icon-topbar-secondary-arrow"></i>
					<div class="inner">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<div class="uk-navbar-dropdown-container">
									<div class="uk-navbar-dropdown-grid uk-child-width-1-5@l uk-child-width-1-4@m uk-flex-center"
										 data-uk-grid data-uk-height-match="target:.uk-nav">
										 <div><ul class="uk-nav uk-navbar-dropdown-nav">';
        }
    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        if (0 === $depth) {
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = array())
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        if (in_array('separate', $classes, false)) {
            $item_output = '';

            apply_filters('walker_nav_menu_end_el', $item_output, $item, $depth, $args);
        }
    }

}