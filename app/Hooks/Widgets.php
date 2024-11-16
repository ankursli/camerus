<?php

namespace App\Hooks;

use App\Widgets\ProductCatList;
use App\Widgets\ProductSalon;
use Themosis\Hook\Hookable;

class Widgets extends Hookable
{
    /**
     * Widgets action hook.
     *
     * @var string
     */
    public $hook = 'widgets_init';

    /**
     * Widgets to register.
     *
     * @var array
     */
    public $widgets = [
		ProductCatList::class,
		ProductSalon::class,
    ];

    /**
     * Register the widgets.
     */
    public function register()
    {
        if (! empty($this->widgets)) {
            foreach ($this->widgets as $widget) {
                register_widget($widget);
            }
        }
    }
}
