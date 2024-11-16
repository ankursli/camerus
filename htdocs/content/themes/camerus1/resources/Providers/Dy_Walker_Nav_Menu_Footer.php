<?php
namespace Theme\Providers;

/**
 * Custom menu footer
 * Class Dy_Walker_Nav_Menu_Footer
 * @package Theme\Providers
 */
class Dy_Walker_Nav_Menu_Footer extends \Walker_Nav_Menu
{

	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$class_names = $value = '';
		$dropdown_attr = $dropdown_class = '';

		$has_children = $args->walker->has_children;

		if ($has_children) {
			$dropdown_attr = 'class=""';
			$dropdown_class = '';
		}

		$classes = empty($item->classes) ? array() : (array)$item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = $dropdown_class;

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$blc = '<li';

		$output .= $indent . $blc . $id . $value . $class_names . '>';

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
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$bal = "<a";
		$bal2 = "</a>";

		$item_output = $args->before;
		$item_output .= $bal . $attributes . ' ' . $dropdown_attr . '>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= $bal2;
		$item_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	public function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"level-" . $depth . "\">\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = array())
	{
		$output .= '</ul>';
	}

	public function end_el(&$output, $item, $depth = 0, $args = array())
	{

	}
}